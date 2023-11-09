<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\Admin\Promotion;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\Admin\PromotionRequest;
use Illuminate\Pagination\LengthAwarePaginator;

class PromotionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->get('per_page', 15);
        $page = $request->get('page', 1);
        $coupon_code = $request->get('coupon');
        $property_id = $request->get('property');
        $pay_setup = $request->get('pay_setup'); // e.g. full | monthly
        
        // sanity check
        $product_group_ids = [];
        if (!empty($property_id)) {
            $property = Property::with('productGroups')->find($property_id);
            $product_group_ids = $property->productGroups->pluck('id')->toArray();
        }

        $promotions = Promotion::with(['promotionRules', 'promotionRewards'])
            ->when(!$request->has('active') || $request->boolean('active'), function ($query) {
                $query->active();
            })
            ->when($request->has('active') && !$request->boolean('active'), function ($query) {
                $query->inactive();
            })
            ->when($request->has('valid'), function ($query) use ($request) {
                return $request->boolean('valid')
                    ? $query->valid()
                    : $query->expired();
            })
            ->when(!empty($coupon_code), function ($query) use ($coupon_code, $pay_setup) {
                // search for promotions with promo rule that has the coupon code
                $query
                    ->rulesMatchAnd()
                    ->whereHas('promotionRules', function ($q) use ($coupon_code) {
                        $q->searchCouponCode($coupon_code);
                    })
                    ->when(!empty($pay_setup), function ($q) use ($pay_setup) {
                        $q->whereHas('promotionRewards', function ($q) use ($pay_setup) {
                            $q->searchQualifiedForPaymentSetup($pay_setup);
                        });
                    });
            })
            ->simplePaginate($per_page);
        
        // filter with product groups wherein the property belongs to
        if (count($product_group_ids) > 0) {
            return $this->filteredWithValidProductGroups($promotions, $product_group_ids, $page, $per_page);
        }

        return response()->json($promotions);
    }

   /**
    * Filter promotions together with valid product groups in promotion rules
    *
    * @param Paginator $promotions
    * @param array $product_group_ids
    * @param integer $page
    * @param integer $per_page
    * @return void
    */
    private function filteredWithValidProductGroups(Paginator $promotions, array $product_group_ids, int $page, int $per_page)
    {
        $items = $promotions->filter(function ($promotion) use ($product_group_ids) {
            $promotion = $promotion->toArray();
            
            // means that coupon code is the only rule
            if (count($promotion['promotion_rules']) === 1) {
                return true;
            }

            // verify each type of product_in_group promotion rule that if the product_group_id
            //  wherein the property belongs to is included in the config
            $qualified = false;
            foreach ($promotion['promotion_rules'] as $rule) {
                if ($rule['type'] != 'product_in_group') {
                    continue;
                }

                $intersect_values = array_intersect($product_group_ids, $rule['config']['product_group_ids']);

                if (count($intersect_values) > 0) {
                    $qualified = true;
                }
            }

            return $qualified;
        });

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $per_page), $items->count(), $per_page, $page, []);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(Promotion $promotion)
    {
        return response()->json([
            'message' => 'Success',
            'data' => $promotion->load([
                'promotionRules',
                'promotionRewards'
            ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\PromotionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromotionRequest $request)
    {
        $promotion = Promotion::create([
            'label' => $request->label,
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'rules_match' => $request->rules_match,
        ]);
        $promotion->save();

        // attach relationships
        $promotion
            ->promotionRules()
            ->attach($request->rule_ids);
        $promotion
            ->promotionRewards()
            ->attach($request->reward_ids);

        return response()->json([
            'message' => 'Promotion successfully added.',
            'data' => $promotion->load([
                'promotionRules',
                'promotionRewards'
            ])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Admin\PromotionRequest  $request
     * @param  \App\Models\Admin\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function update(PromotionRequest $request, Promotion $promotion)
    {
        $promotion->label = $request?->label ?? $promotion->label;
        $promotion->status = $request?->status ?? $promotion->status;
        $promotion->date_from = $request?->date_from ?? $promotion->date_from;
        $promotion->date_to = $request?->date_to ?? $promotion->date_to;
        $promotion->rules_match = $request?->rules_match ?? $promotion->rules_match;
        $promotion->save();

        // sync relationships
        $promotion
            ->promotionRules()
            ->sync($request->rule_ids);
        $promotion
            ->promotionRewards()
            ->sync($request->reward_ids);

        return response()->json([
            'message' => 'Promotion successfully updated.',
            'data' => $promotion->load([
                'promotionRules',
                'promotionRewards'
            ])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return response([
            'message' => 'Promotion successfully deleted.'
        ]);
    }
}

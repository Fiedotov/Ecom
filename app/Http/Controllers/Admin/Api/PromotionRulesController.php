<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Models\Admin\PromotionRule;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PromotionRuleRequest;

class PromotionRulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $coupon_code = $request->get('coupon');

        $promotion_rules = PromotionRule::with('productGroups')
            ->when(!empty($coupon_code), function ($query) use ($coupon_code) {
                return $query->searchCouponCode($coupon_code);
            })
            ->simplePaginate(15);

        return response()->json($promotion_rules);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\PromotionRule  $promotion_rule
     * @return \Illuminate\Http\Response
     */
    public function show(PromotionRule $promotion_rule)
    {
        return response()->json([
            'message' => 'Success',
            'data' => $promotion_rule->load('productGroups')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\PromotionRuleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromotionRuleRequest $request)
    {
        $promotion_rule = PromotionRule::create([
            'type' => $request->type,
            'config' => $request->config
        ]);

        // attach relationship
        if ($request->type == 'product_in_group') {
            $promotion_rule
                ->productGroups()
                ->attach($request->config['product_group_ids']);
        }

        return response()->json([
            'message' => 'Promotion successfully added.',
            'data' => $promotion_rule->load('productGroups')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Admin\PromotionRuleRequest  $request
     * @param  \App\Models\Admin\PromotionRule  $promotion_rule
     * @return \Illuminate\Http\Response
     */
    public function update(PromotionRuleRequest $request, PromotionRule $promotion_rule)
    {
        $promotion_rule->type = $request?->type ?? $promotion_rule->type;
        $promotion_rule->config = $request?->config ?? $promotion_rule->config;
        $promotion_rule->save();

        // sync relationship
        if ($request->type == 'product_in_group') {
            $promotion_rule
                ->productGroups()
                ->sync($request->config['product_group_ids']);
        }

        return response()->json([
            'message' => 'Promotion rule successfully updated.',
            'data' => $promotion_rule->load('productGroups')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\PromotionRule  $promotion_rule
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromotionRule $promotion_rule)
    {
        $promotion_rule->delete();

        return response([
            'message' => 'Promotion rule successfully deleted.'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SearchLandForSale
{
    public function __invoke(): Response
    {
        return Inertia::render('SearchLandForSale/Index')->with([
            'states' => $this->getStates(),
            'filter_settings' => DB::table('properties')
                ->selectRaw("MIN(payment_1) as min_monthly_payment, MAX(payment_1) as max_monthly_payment")
                ->selectRaw("MIN(cash_price_current) as min_price, MAX(cash_price_current) as max_price")
                ->selectRaw("MIN(acreage) as min_acreage, MAX(acreage) as max_acreage")
                ->where('status', Property::STATUS_AVAILABLE)
                ->first(),
            'points' => PropertyResource::collection(
                Property::query()
                    ->whereNotNull('latitude')
                    ->where('status', Property::STATUS_AVAILABLE)
                    ->with('images')
                    ->get()
            )
        ]);
    }

    private function getStates(): Collection
    {
        $properties = DB::table('properties')->orderBy('state')->where('status', Property::STATUS_AVAILABLE)->get();

        return $properties->groupBy('state')
            ->map(fn(Collection $properties) => [
                'name' => $properties->first()->state,
                'property_count' => $properties->count(),
                'counties' => $properties->groupBy('county')
                    ->sortKeys()
                    ->map(fn(Collection $properties) => [
                        'name' => $properties->first()->county,
                        'property_count' => $properties->count(),
                    ])
                    ->values()
            ])->values();
    }
}

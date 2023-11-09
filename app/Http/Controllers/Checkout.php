<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Property;
use Illuminate\Support\Carbon;
use App\Models\Admin\Promotion;
use App\Models\Admin\ProductGroup;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\PropertyResource;
use App\Http\Requests\Checkout as CheckoutRequest;

class Checkout
{
    public function __invoke(CheckoutRequest $request, Property $property): Response|RedirectResponse
    {
        if ($property->status !== Property::STATUS_AVAILABLE) {
            return response()->redirectTo('/');
        }

        return Inertia::render('Checkout/Checkout', [
            'property' => new PropertyResource($property),
            'checkout' => [
                'paymentCount' => $request->paymentCount(),
                'lineItems' => $request->lineItems(),
            ],
            'first_renewal_date' => Carbon::now()->addMonthNoOverflow()->toDateString(),
        ]);
    }
}

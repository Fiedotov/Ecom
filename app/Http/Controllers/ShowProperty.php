<?php

namespace App\Http\Controllers;

use App\Http\Resources\PropertyResource;
use App\Models\Property;
use App\YouMightAlsoLikeProperties;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShowProperty
{
    public function __invoke(Request $request, Property $property): RedirectResponse|Response
    {
        if (! ($property->isAvailable() || $property->isSold() || $property->isPending())) {
            return response()->redirectTo('/');
        }

        return Inertia::render('ShowProperty/Index', [
            'property' => new PropertyResource($property),
            'other_properties' => (new YouMightAlsoLikeProperties())($property)
        ]);
    }
}

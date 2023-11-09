<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;

class ShowPropertyImage
{
    public function __invoke(Request $request): Response
    {
        $property = Property::query()->find($request->id);

        if (Cache::has("property-image.{$property->property_id}")) {
            $contents = Cache::get("property-image.{$property->property_id}");
        } else {
            $contents = file_get_contents($property->google_maps_url);

            Cache::put("property-image.{$property->property_id}", $contents);
        }

        return response($contents, 200, ['Content-type' => 'image/jpg']);
    }
}

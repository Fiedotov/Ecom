<?php

namespace App;

use App\Models\Property;
use Illuminate\Support\Collection;

class YouMightAlsoLikeProperties
{
    public function __invoke(Property $property): Collection
    {
        return Property::query()
            ->where('id', '!=', $property->id)
            ->where('status', Property::STATUS_AVAILABLE)
            ->with(['images'])
            ->inRandomOrder()
            ->limit(12)
            ->get();
    }
}

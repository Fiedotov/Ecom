<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request),
            [
                'title' => $this->resource->isAvailable()
                    ? $this->resource->title
                    : sprintf('Sold!!! %s', $this->resource->title),
                'is_available' => $this->resource->isAvailable(),
                'original_price' => $this->resource->getOriginalPriceAttribute(),
                'discount_amount' => $this->resource->getDiscountAmountAttribute(),
                'cash_total' => $this->resource->getCashTotalAttribute(),
                'document_fee' => config('discount.document_fee'),
                'description' => collect(explode("\r\n", $this->resource->description))->filter()->values()->toArray(),
                'usage' => array_map(
                    fn(string $usage) => [
                        'name' => $usage,
                        'icon' => sprintf('/img/usage/%s.png', Str::slug($usage))
                    ],
                    array_values(array_filter(preg_split('/<br>;?/', $this->resource->usage)))
                ),
        ]);
    }
}

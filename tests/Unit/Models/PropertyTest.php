<?php

namespace Tests\Unit\Models;

use App\Models\Property;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @dataProvider providePricing
     */
    public function test_it_can_determine_if_a_property_is_discounted(float $listPrice, ?float $salePrice, bool $discounted)
    {
        $property = $this->createProperty([
            'property_list_price' => $listPrice,
            'cash_sale_price' => $salePrice,
        ]);

        $this->assertSame($property->isDiscounted(), $discounted);
    }

    public function providePricing(): array
    {
        return [
            'Discounted' => [20000.00, 18000.00, true],
            'Not Discounted' => [20000.00, 20000.00, false],
            'Not Discounted Also' => [20000.00, null, false],
        ];
    }

    protected function createProperty(array $attributes = []): Property
    {
        return Property::factory()->create($attributes);
    }

    /**
     * @dataProvider provideDiscountAmounts
     */
    public function test_it_can_determine_discount_amount(float $list, ?float $sale, float $discount)
    {
        $property = $this->createProperty([
            'property_list_price' => $list,
            'cash_sale_price' => $sale,
        ]);

        $this->assertEquals($discount, $property->getDiscount());
    }

    public function provideDiscountAmounts(): array
    {
        return [
            '$4,000' => [20000.00, 16000.00, 4000.00],
            '$0' => [20000.00, 20000.00, 0],
            'NULL $0' => [20000.00, null, 0],
        ];
    }
}

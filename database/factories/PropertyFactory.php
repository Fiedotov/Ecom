<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'property_id' => $this->faker->phoneNumber,
            'name' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['Available - Website']),
            'acreage' => $this->faker->randomFloat(0, 1, 20),
            'zoning' => $this->faker->randomElement(['Residential', 'Commercial']),
            'property_list_price' => $this->faker->numberBetween(10000, 100000),
            'payment_1' => $this->faker->numberBetween(1, 100),
            'payment_2' => $this->faker->numberBetween(2, 200),
            'payment_3' => $this->faker->numberBetween(3, 300),
            'city' => $this->faker->city,
            'county' => $this->faker->word,
            'state' => $this->faker->randomElement(['TX', 'FL', 'CA', 'NY']),
            'zip_code' => $this->faker->postcode,
            'property_description' => $this->faker->sentence,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'apn' => $this->faker->numberBetween(1, 1000),
            'annual_property_taxes' => $this->faker->numberBetween(100, 10000),
            'hoa_poa_annual_fee' => $this->faker->numberBetween(1, 500),
            'water_connection' => $this->faker->boolean(30),
            'sewer_connection' => $this->faker->boolean(30),
            'power_connection' => $this->faker->boolean(30),
            'road_access' => false,
        ];
    }
}

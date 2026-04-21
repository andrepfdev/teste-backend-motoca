<?php

namespace Database\Factories;

use App\Enums\VehicleType;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(VehicleType::cases())->value;

        $models = $type === VehicleType::Motorcycle->value
            ? ['CG 160 Titan', 'CB 300F Twister', 'CB 500 Hornet', 'CBR 650R', 'PCX 160', 'XRE 300 Sahara', 'Gold Wing']
            : ['City Hatchback', 'Fit', 'Civic', 'Accord', 'HR-V', 'ZR-V', 'CR-V', 'WR-V', 'Pilot', 'Ridgeline', 'NSX', 'S2000', 'Integra Type R'];

        return [
            'type' => $type,
            'brand' => 'Honda',
            'model' => $this->faker->randomElement($models),
            'year' => $this->faker->numberBetween(2000, (int) date('Y')),
            'price' => $this->faker->randomFloat(2, 5000, 150000),
            'color' => $this->faker->safeColorName(),
            'mileage' => $this->faker->numberBetween(0, 200000),
        ];
    }
}

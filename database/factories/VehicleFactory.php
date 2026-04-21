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
        return [
            'type' => $this->faker->randomElement(VehicleType::cases())->value,
            'brand' => $this->faker->randomElement(['Honda', 'Toyota', 'Ford', 'Chevrolet', 'Volkswagen', 'Yamaha']),
            'model' => $this->faker->word(),
            'year' => $this->faker->numberBetween(2000, (int) date('Y')),
            'price' => $this->faker->randomFloat(2, 5000, 150000),
            'color' => $this->faker->safeColorName(),
            'mileage' => $this->faker->numberBetween(0, 200000),
        ];
    }
}

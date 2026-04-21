<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['Carro', 'Moto', 'Caminhão']),
            'brand' => $this->faker->randomElement(['Toyota', 'Honda', 'Ford', 'Chevrolet', 'Volkswagen']),
            'model' => $this->faker->word(),
            'year' => $this->faker->numberBetween(1990, 2024),
            'price' => $this->faker->randomFloat(2, 5000, 100000),
            'color' => $this->faker->safeColorName(),
            'mileage' => $this->faker->numberBetween(0, 200000),
        ];
    }
}

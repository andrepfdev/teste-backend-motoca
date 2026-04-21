<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $vehicles = Vehicle::factory(15)->create();

        $vehicles->each(function (Vehicle $vehicle) {
            Lead::factory(rand(0, 5))->create(['vehicle_id' => $vehicle->id]);
        });
    }
}

<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/dashboard')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['total_vehicles', 'total_leads', 'most_requested_vehicle'],
            ]);
    }

    public function test_unauthenticated_cannot_access_dashboard(): void
    {
        $this->getJson('/api/dashboard')->assertStatus(401);
    }

    public function test_dashboard_returns_correct_counts(): void
    {
        $user = User::factory()->create();
        $vehicles = Vehicle::factory(4)->create();
        Lead::factory(7)->recycle($vehicles)->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('data.total_vehicles', 4)
            ->assertJsonPath('data.total_leads', 7);
    }

    public function test_dashboard_returns_most_requested_vehicle(): void
    {
        $user = User::factory()->create();

        $popular = Vehicle::factory()->create();
        $other = Vehicle::factory()->create();

        Lead::factory(5)->create(['vehicle_id' => $popular->id]);
        Lead::factory(1)->create(['vehicle_id' => $other->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('data.most_requested_vehicle.id', $popular->id);
    }

    public function test_dashboard_most_requested_vehicle_is_null_when_no_vehicles(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('data.most_requested_vehicle', null);
    }
}

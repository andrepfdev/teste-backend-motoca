<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use LazilyRefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // ── Listagem ───────────────────────────────────────────────────────────────

    public function test_authenticated_user_can_list_vehicles(): void
    {
        Vehicle::factory(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/vehicles');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(3, 'data');
    }

    public function test_unauthenticated_cannot_list_vehicles(): void
    {
        $this->getJson('/api/vehicles')->assertStatus(401);
    }

    public function test_can_filter_vehicles_by_type(): void
    {
        Vehicle::factory(2)->create(['type' => 'car']);
        Vehicle::factory(3)->create(['type' => 'motorcycle']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/vehicles?type=car');

        $response->assertStatus(200)->assertJsonCount(2, 'data');
    }

    public function test_can_filter_vehicles_by_max_price(): void
    {
        Vehicle::factory()->create(['price' => 50000]);
        Vehicle::factory()->create(['price' => 100000]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/vehicles?max_price=60000');

        $response->assertStatus(200)->assertJsonCount(1, 'data');
    }

    public function test_can_filter_vehicles_by_min_price(): void
    {
        Vehicle::factory()->create(['price' => 20000]);
        Vehicle::factory()->create(['price' => 80000]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/vehicles?min_price=50000');

        $response->assertStatus(200)->assertJsonCount(1, 'data');
    }

    // ── Criação ────────────────────────────────────────────────────────────────

    public function test_authenticated_user_can_create_vehicle(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/vehicles', [
                'type' => 'car',
                'brand' => 'Honda',
                'model' => 'Civic',
                'year' => 2022,
                'price' => 85000,
                'color' => 'prata',
                'mileage' => 0,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.model', 'Civic');

        $this->assertDatabaseHas('vehicles', ['model' => 'Civic']);
    }

    public function test_create_vehicle_fails_with_invalid_data(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/vehicles', [
                'type' => 'invalid',
                'brand' => '',
                'model' => '',
                'year' => 1800,
                'price' => -1,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type', 'brand', 'model', 'year', 'price']);
    }

    public function test_unauthenticated_cannot_create_vehicle(): void
    {
        $this->postJson('/api/vehicles', [])->assertStatus(401);
    }

    // ── Exibição ───────────────────────────────────────────────────────────────

    public function test_authenticated_user_can_get_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $vehicle->id);
    }

    public function test_get_vehicle_returns_404_for_nonexistent(): void
    {
        $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/vehicles/999')
            ->assertStatus(404);
    }

    // ── Atualização ────────────────────────────────────────────────────────────

    public function test_authenticated_user_can_update_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create(['price' => 50000]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/vehicles/{$vehicle->id}", ['price' => 45000]);

        $response->assertStatus(200)
            ->assertJsonPath('data.price', '45000.00');
    }

    public function test_unauthenticated_cannot_update_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create();

        $this->putJson("/api/vehicles/{$vehicle->id}", ['price' => 1])
            ->assertStatus(401);
    }

    // ── Remoção ────────────────────────────────────────────────────────────────

    public function test_authenticated_user_can_delete_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create();

        $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/vehicles/{$vehicle->id}")
            ->assertStatus(200);

        $this->assertModelMissing($vehicle);
    }

    public function test_unauthenticated_cannot_delete_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create();

        $this->deleteJson("/api/vehicles/{$vehicle->id}")
            ->assertStatus(401);
    }

    // ── Leads por Veículo ──────────────────────────────────────────────────────

    public function test_authenticated_user_can_list_leads_by_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create();
        Lead::factory(3)->create(['vehicle_id' => $vehicle->id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/vehicles/{$vehicle->id}/leads");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_leads_by_vehicle_returns_only_vehicle_leads(): void
    {
        $vehicle = Vehicle::factory()->create();
        Lead::factory(2)->create(['vehicle_id' => $vehicle->id]);
        Lead::factory(3)->create(); // leads de outros veículos

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/vehicles/{$vehicle->id}/leads");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }
}

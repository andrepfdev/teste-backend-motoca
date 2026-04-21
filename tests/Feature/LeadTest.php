<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use LazilyRefreshDatabase;

    // ── Criação (pública) ──────────────────────────────────────────────────────

    public function test_anyone_can_create_lead(): void
    {
        $vehicle = Vehicle::factory()->create();

        $response = $this->postJson('/api/leads', [
            'name' => 'Maria Silva',
            'email' => 'maria@example.com',
            'phone' => '(11) 99999-0000',
            'vehicle_id' => $vehicle->id,
            'message' => 'Tenho interesse.',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.email', 'maria@example.com');

        $this->assertDatabaseHas('leads', ['email' => 'maria@example.com']);
    }

    public function test_create_lead_without_message(): void
    {
        $vehicle = Vehicle::factory()->create();

        $response = $this->postJson('/api/leads', [
            'name' => 'João',
            'email' => 'joao@example.com',
            'phone' => '(11) 98888-0000',
            'vehicle_id' => $vehicle->id,
        ]);

        $response->assertStatus(201);
    }

    public function test_create_lead_fails_with_invalid_data(): void
    {
        $response = $this->postJson('/api/leads', [
            'name' => '',
            'email' => 'not-an-email',
            'phone' => '',
            'vehicle_id' => null,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'phone', 'vehicle_id']);
    }

    public function test_create_lead_fails_with_nonexistent_vehicle(): void
    {
        $response = $this->postJson('/api/leads', [
            'name' => 'Maria',
            'email' => 'maria@example.com',
            'phone' => '(11) 99999-0000',
            'vehicle_id' => 999,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['vehicle_id']);
    }

    // ── Listagem (protegida) ───────────────────────────────────────────────────

    public function test_authenticated_user_can_list_leads(): void
    {
        Lead::factory(5)->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/leads');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(5, 'data');
    }

    public function test_unauthenticated_cannot_list_leads(): void
    {
        $this->getJson('/api/leads')->assertStatus(401);
    }
}

<?php

namespace Tests\Feature;

use App\Domains\User\Models\User;
use App\Domains\User\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanningApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_simulate_margin()
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id
        ]);

        $response = $this->actingAs($user)->postJson('/api/planning/simulate', [
            'total_cost' => 100000,
            'target_margin' => 20
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'original_cost' => 100000,
                     'simulated_total' => 125000, // 100000 / (1 - 0.2)
                     'variance_percent' => 20
                 ]);
    }
}

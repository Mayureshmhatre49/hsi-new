<?php

namespace Tests\Feature;

use App\Domains\User\Models\User;
use App\Domains\User\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_projects()
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id
        ]);

        $response = $this->actingAs($user)->getJson('/api/projects');

        $response->assertStatus(200);
    }
}

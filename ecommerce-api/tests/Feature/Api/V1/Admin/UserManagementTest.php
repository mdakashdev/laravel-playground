<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);
    }

    public function test_admin_can_access_admin_route(): void
    {
        $admin = User::factory()->create();

        // Existing method অনুযায়ী admin role assign করো
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)
            ->getJson('/api/v1/admin/users');

        $response->assertStatus(200);
    }

    public function test_customer_cannot_access_admin_route(): void
    {
        $customer = User::factory()->create();

        $customer->assignRole('customer');

        $response = $this->actingAs($customer)
            ->getJson('/api/v1/admin/users');

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_route(): void
    {
        $response = $this->getJson('/api/v1/admin/users');

        $response->assertStatus(401);
    }
}

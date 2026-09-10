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
    use RefreshDatabase; // er kaj hocche test run korar age db ke clean kore dibe

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        // ekhane 2 ta seeder calalam
        // jar kaj hocche - permission create kora & role create kora with assign

        $this->user = User::factory()->create();
    }

    public function test_admin_can_access_admin_route(): void
    {
        $admin = User::factory()->create(); // akta user create korbe

        // Existing method অনুযায়ী admin role assign করো
        $admin->assignRole('admin'); // jei user create korlam take admin role assign korbo

        $response = $this->actingAs($admin)
            ->getJson('/api/v1/admin/users'); //admin hisabe ei endpoint ke browse korbe

        $response->assertStatus(200); // then succes status dibe
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

    public function test_authorized_admin_can_access_user_list(): void
    {
        //$user = User::factory()->create();

        //role assign
        $this->user->assignRole('admin');

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/admin/users');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ])
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data'
                 ]);
    }

    public function test_user_without_users_view_permission_gets_403(): void
    {
        $user = User::factory()->create();

        //role assign
        $user->assignRole('customer');

        $response = $this->actingAs($user)
            ->getJson('/api/v1/admin/users');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_gets_401(): void
    {
        $response = $this->getJson('/api/v1/admin/users');

        $response->assertStatus(401);
    }

    public function test_authorized_admin_can_get_user_details(): void
    {
        // role assign
        $this->user->assignRole('admin');

        $reespone = $this->actingAs($this->user)
                ->getJson('/api/v1/admin/users/'.$this->user->id);

        $reespone->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ])
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'name',
                         'email',
                         'email_verified_at',
                         'roles',
                         'created_at'
                     ]
                 ]);
    }
}

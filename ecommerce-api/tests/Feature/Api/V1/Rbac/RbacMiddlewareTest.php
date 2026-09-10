<?php

namespace Tests\Feature\Api\V1\Rbac;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacMiddlewareTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
            RoleSeeder::class
        ]);

    }


//উদ্দেশ্য হলো permission middleware ঠিকভাবে 200 এবং 403 return করছে কিনা, আর multiple permission দিলে behavior কী হচ্ছে সেটা verify করা।

    public function test_user_with_users_view_permission_can_access_users()
    {
        // create user
        $user = User::factory()->create();

        // permission added
        $user->givePermissionTo('users.view');

        //browse endpoint
        $response = $this->actingAs($user)
            ->getJson('/api/v1/admin/users');

        //assertion
        $response->assertStatus(200);
    }

    public function test_user_with_users_view_multiple_permission_can_access_users()
    {
        // create user
        $user = User::factory()->create();

        // permission added
        $user->givePermissionTo([
            'users.view',
            'users.update',
        ]);

        //browse endpoint
        $response = $this->actingAs($user)
            ->getJson('/api/v1/admin/users')
            ->assertStatus(200);
    }

    public function test_user_without_users_view_permission_gets_403()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/v1/admin/users')
            ->assertStatus(403)
            ->assertSee('You do not have permission to access this resource.');
    }

}

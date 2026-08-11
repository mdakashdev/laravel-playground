<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class
        ]);
        //RoleSeeder চালানোর আগে PermissionSeeder চালানো থাকতে হবে, কারণ role-গুলোর মধ্যে যে permissions assign করবে সেগুলো database-এ আগে থাকতে হবে।
    }
}

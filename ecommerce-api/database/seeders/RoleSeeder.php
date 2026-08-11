<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create roles
        $admin = Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $manager = Role::firstOrCreate([
            'name' => 'manager'
        ]);

        $customer = Role::firstOrCreate([
            'name' => 'customer'
        ]);

        // Assign Permissions

        // Admin -> all 12 permissions
        $admin->syncPermissions(
            Permission::pluck('name')->toArray()
        );

        // Manager
        $manager->syncPermissions(
            'users.view',
            'users.update',

            'products.view',
            'products.create',
            'products.update',

            'orders.view',
            'orders.update',
        );

        // Customer
        $customer->syncPermissions(
            'products.view',

            'orders.view',
            'orders.create'
        );


// আর syncPermissions() ব্যবহার করার ফলে role-এর permission assignment ঠিকভাবে sync হবে।

    }
}

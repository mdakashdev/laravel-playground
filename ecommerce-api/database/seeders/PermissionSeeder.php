<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Spatie-এর Permission model দিয়ে ১২টা permission তৈরি করা, তবে duplicate যেন না হয়।
        //users, product and order

        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            'products.view',
            'products.create',
            'products.update',
            'products.delete',

            'orders.view',
            'orders.create',
            'orders.update',
            'orders.delete'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        //এটা Spatie-এর Permission model import করছে। তারপর ১২টা permission array-এর মধ্যে রাখা হয়েছে।
    }
}

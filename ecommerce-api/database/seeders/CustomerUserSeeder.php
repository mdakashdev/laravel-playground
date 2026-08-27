<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            [
                'email' => 'customer@example.com',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Customer',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('customer');
    }
}

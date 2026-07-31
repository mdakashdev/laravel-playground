<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Str;

class RegisterUserAction
{
    public function execute(array $data): User
    {
        return User::create([
            'uuid' => Str::uuid(),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], //automatic cast korbe from cast
            'phone' => $data['phone'] ?? null,
            'status' => true,
            'avatar' => null
        ]);
    }
}

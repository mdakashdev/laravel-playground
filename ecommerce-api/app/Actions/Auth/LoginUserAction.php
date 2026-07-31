<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Nette\Schema\ValidationException;

class LoginUserAction
{
    public function execute(array $credentials): User
    {
        // User খুঁজবে email দিয়ে
        $user = User::where('email', $credentials['email'])->first();

        //* Password verify করবে & * Invalid credentials হলে exception throw করবে
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // User return করবে
        return $user;
    }
}

<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email)
    {
        return 'findByEmail';
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }
    public function changePassword(User $user, string $password): User
    {
        $user->update([
            'password' => $password,
        ]);

        return $user;
    }
}

// talk with database

<?php

namespace App\Actions\Admin;

use App\Models\User;

class AssignUserRoleAction
{
    public function execute(User $user, string $role): void
    {
        // User -> Remove existing role -> Assign requested role
        $user->syncRoles($role);

        //`assignRole()` ব্যবহার না করে এখানে `syncRoles()` ব্যবহার করবে, কারণ আমরা চাই user-এর **current role replace** হোক।
    }
}

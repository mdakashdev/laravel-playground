<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\AssignUserRoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\AssignRoleRequest;
use App\Http\Resources\Api\V1\AdminResource;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        $users = User::with('roles') // i think ei relation er data ta ene dicche hasRole trait - pore dekhbo
                    ->latest()
                    ->paginate(15);

        return ApiResponse::success(
            'Users retrieved successfully.',
            $users
        );
    }

    public function show(User $user) {
        $user->load('roles');

        return ApiResponse::success(
            'Users retrieved successfully.',
            new AdminResource($user)
        );
    }

    public function assignRole(
        AssignRoleRequest $request,
        User $user,
        AssignUserRoleAction $action
    ) {
        //dd($request->validated('role'));
        $action->execute(
            $user,
            $request->validated('role')
        );

        $user->load('roles');

        return ApiResponse::success(
            'User role updated successfully.',
            [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->getRoleNames()->first(),
            ]
        );

    }
}

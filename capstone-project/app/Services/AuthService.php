<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * register new user
     */
    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            //Hash the password
            $data['password'] = bcrypt($data['password']);

            //create the user through the repository
            $user = $this->userRepository->createUser($data);

            return $user;
        });
    }

    /**
     * @throws ValidationException
     */
    public function login(array $credentials): array
    {
        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => new UserResource($user),
            'token' => $token
        ];
    }

    public function updateProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
           return $this->userRepository->updateProfile($user, $data);
        });
    }

    public function changePassword(User $user, array $data)
    {
        //manually password verify without request file rules - current_password
//        if (! Hash::check($data['current_password'], $user->password)) {
//            throw ValidationException::withMessages([
//                'current_password' => [
//                    'The current password is incorrect.'
//                ],
//            ]);
//        }

        $this->userRepository->changePassword(
            $user,
            Hash::make($data['password']) //new password hashing
        );

        return response()->noContent();
    }

    //talk with business logic

}

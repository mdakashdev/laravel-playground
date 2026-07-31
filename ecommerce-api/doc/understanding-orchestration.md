আপনার architecture অনুযায়ী flow হবে:

```text
HTTP Request
      │
      ▼
RegisterRequest (Validation)
      │
      ▼
AuthController
      │
      ▼
AuthService (Orchestration)
      │
      ▼
RegisterUserAction (Business Logic)
      │
      ▼
User::create()
      │
      ▼
User Model
      │
      ▼
AuthController
      │
      ▼
ApiResponse
```

---

## 1. RegisterRequest

Validation করবে।

```php
public function rules(): array
{
    return [
        'name' => 'required|string|min:3|max:100',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:8',
    ];
}
```

---

## 2. AuthController

এখানে কোনো business logic থাকবে না।

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use App\Helpers\ApiResponse;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register(
            $request->validated()
        );

        return ApiResponse::success(
            'Registration successful.',
            $user
        );
    }
}
```

দেখুন, Controller শুধু ৩টা কাজ করছে:

* Request নিচ্ছে
* Service call করছে
* Response দিচ্ছে

---

## 3. AuthService

```php
class AuthService
{
    public function __construct(
        protected RegisterUserAction $registerUserAction
    ) {}

    public function register(array $data): User
    {
        return $this->registerUserAction->execute($data);
    }
}
```

এখন শুধু orchestration।

---

## 4. RegisterUserAction

```php
class RegisterUserAction
{
    public function execute(array $data): User
    {
        return User::create([
            'uuid' => Str::uuid(),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'status' => true,
            'avatar' => null,
        ]);
    }
}
```

এখানেই business logic।

---

## তাহলে Service কেন?

এখন তো মনে হচ্ছে Service শুধু pass-through:

```text
Controller
      ↓
Service
      ↓
Action
```

এটা ইচ্ছাকৃত।

ধরুন ২ মাস পরে registration-এর সময় আরও কাজ লাগবে:

* User create
* Default role assign
* Send verification email
* Dispatch event
* Create profile

তখন:

```php
public function register(array $data): User
{
    $user = $this->registerUserAction->execute($data);

    $this->assignRoleAction->execute($user);

    $this->createProfileAction->execute($user);

    $this->sendVerificationEmailAction->execute($user);

    return $user;
}
```

Controller-এর এক লাইনও পরিবর্তন করতে হবে না।

---

## ভবিষ্যতে Architecture

```text
Controller
      │
      ▼
AuthService
      │
      ├── RegisterUserAction
      ├── AssignRoleAction
      ├── CreateProfileAction
      ├── SendVerificationEmailAction
      ├── LogActivityAction
      └── DispatchRegisteredEventAction
```

এটাই Service Layer-এর আসল উদ্দেশ্য।

### দায়িত্বগুলো সংক্ষেপে

| Layer          | দায়িত্ব                                     |
| -------------- | -------------------------------------------- |
| **Controller** | Request গ্রহণ, Service call, Response return |
| **Service**    | একাধিক Action orchestrate করা                |
| **Action**     | একটি নির্দিষ্ট business logic সম্পন্ন করা    |
| **Model**      | Database-এর সাথে কাজ করা                     |

এভাবে প্রতিটি layer-এর দায়িত্ব পরিষ্কার থাকে, ফলে কোড maintain ও extend করা অনেক সহজ হয়।

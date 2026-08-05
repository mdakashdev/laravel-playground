এই Task-এ তোমার `tests/Feature/Api/V1/Auth/AuthTest.php`-এ authentication flow-এর end-to-end feature test লিখতে হবে। Laravel-এর `RefreshDatabase` ব্যবহার করলে প্রতিটি test-এর আগে database fresh হবে।

সাধারণ structure:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
```

অথবা PHPUnit হলে:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
}
```

### Register

**User can register successfully**

```php
$response = $this->postJson('/api/v1/register', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
]);

$response
    ->assertCreated()
    ->assertJsonStructure([
        'success',
        'message',
        'data',
    ]);

$this->assertDatabaseHas('users', [
    'email' => 'john@example.com',
]);
```

**Email must be unique**

প্রথমে user create করো, তারপর একই email দিয়ে register:

```php
User::factory()->create([
    'email' => 'john@example.com',
]);

$response = $this->postJson('/api/v1/register', [
    'name' => 'John',
    'email' => 'john@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
]);

$response
    ->assertStatus(422)
    ->assertJsonStructure([
        'success',
        'message',
        'errors',
    ]);
```

---

### Login

```php
$user = User::factory()->create([
    'password' => Hash::make('password123'),
]);

$response = $this->postJson('/api/v1/login', [
    'email' => $user->email,
    'password' => 'password123',
]);

$response
    ->assertOk()
    ->assertJsonStructure([
        'success',
        'message',
        'data' => [
            'token',
        ],
    ]);
```

Invalid password:

```php
$response->assertUnauthorized();
```

---

### Protected Route (Me)

Authenticated:

```php
$token = $user->createToken('test')->plainTextToken;

$response = $this
    ->withToken($token)
    ->getJson('/api/v1/me');

$response->assertOk();
```

Unauthenticated:

```php
$this->getJson('/api/v1/me')
    ->assertUnauthorized();
```

---

### Logout

```php
$token = $user->createToken('test')->plainTextToken;

$response = $this
    ->withToken($token)
    ->postJson('/api/v1/logout');

$response->assertOk();

$this->assertCount(0, $user->fresh()->tokens);
```

---

### Email Verification

Notification fake করলে email send হয়েছে কিনা verify করা সহজ হবে।

```php
Notification::fake();
```

Unverified user:

```php
$response->assertOk();
```

Verified user:

```php
$user->markEmailAsVerified();

$response->assertStatus(400);
```

---

### Forgot Password

Notification fake করো:

```php
Notification::fake();
```

Valid email:

```php
$response->assertOk();
```

Unknown email:

```php
$response->assertStatus(404);
```

---

### Reset Password

Laravel-এর broker দিয়ে token generate করো:

```php
$token = Password::createToken($user);
```

তারপর:

```php
$response = $this->postJson('/api/v1/reset-password', [
    'email' => $user->email,
    'token' => $token,
    'password' => 'newpassword123',
    'password_confirmation' => 'newpassword123',
]);

$response->assertOk();

$this->assertTrue(
    Hash::check('newpassword123', $user->fresh()->password)
);
```

Invalid token:

```php
$response->assertStatus(400);
```

---

### Response Structure

প্রতিটি endpoint-এ অন্তত এগুলো assert করো।

Success:

```php
$response->assertJsonStructure([
    'success',
    'message',
    'data',
]);
```

Error:

```php
$response->assertJsonStructure([
    'success',
    'message',
    'errors',
]);
```

---

শেষে সব test চালাও:

```bash
docker compose exec app php artisan test
```

যদি তুমি **Pest** ব্যবহার করো, test syntax আলাদা হবে। আর যদি **PHPUnit** ব্যবহার করো, `AuthTest.php`-টা class-based হবে।



হ্যাঁ, এটা PHPUnit-এর default generated test। এখন `test_example()` মুছে দিয়ে তোমার authentication testগুলো লিখবে।

আর একটা জিনিস খেয়াল করো:

```php
namespace Tests\Feature\Feature\Api\V1\Auth;
```

এখানে `Feature` দুইবার এসেছে। যদি file path হয়:

```text
tests/Feature/Api/V1/Auth/AuthTest.php
```

তাহলে namespace হওয়া উচিত:

```php
namespace Tests\Feature\Api\V1\Auth;
```

---

তোমার class শুরুটা হবে:

```php
<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $response = $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }
}
```

এরপর একই class-এ login, logout, me, forgot password, reset password ইত্যাদির test method যোগ করবে।

**একটা প্রশ্ন:** তুমি কি **Laravel 12 + PHPUnit** ব্যবহার করছ, নাকি **Pest**?



শুধু **Feature Test** চালাতে চাইলে:

```bash
docker compose exec app php artisan test --testsuite=Feature
```

অথবা Laravel-এর filter দিয়ে:

```bash
docker compose exec app php artisan test tests/Feature
```

শুধু তোমার `AuthTest` চালাতে চাইলে:

```bash
docker compose exec app php artisan test tests/Feature/Api/V1/Auth/AuthTest.php
```

আর নির্দিষ্ট একটি test method চালাতে:

```bash
docker compose exec app php artisan test --filter=user_can_register_successfully
```

তোমার ক্ষেত্রে development-এর সময় সবচেয়ে useful হবে:

```bash
docker compose exec app php artisan test tests/Feature/Api/V1/Auth/AuthTest.php
```

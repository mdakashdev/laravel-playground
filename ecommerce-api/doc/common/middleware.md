# Middleware

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // User authenticated?
        if (! $request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // Required role আছে?
        if (! $request->user()->hasAnyRole($roles)) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        return $next($request);
    }
}
```

### $roles

এখানে গুরুত্বপূর্ণ অংশ:

```php
...$roles
```

এটার কারণে multiple role নেওয়া যাবে।

যেমন:

```text
role:admin
```

এলে:

```php
$roles = ['admin'];
```

আর:

```text
role:admin,manager
```

এলে:

```php
$roles = ['admin', 'manager'];
```

তারপর:

```php
$request->user()->hasAnyRole($roles)
```

check করবে user-এর **admin অথবা manager** role আছে কিনা।

---

### Step 3 — Middleware register

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'role' => RoleMiddleware::class,
    ]);
})
```

### Route

```php
Route::middleware('role:admin')->group(function () {
    // admin only
});
```

অথবা:

```php
Route::middleware('role:admin,manager')->group(function () {
    // admin OR manager
});
```

### hasAnyRole

**একটা জিনিস খেয়াল রাখবে:** `hasAnyRole()` কাজ করার জন্য তোমার `User` model-এ Spatie-এর `HasRoles` trait থাকতে হবে:

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```


## how to pass parameter and get this

> Laravel route middleware parameter-টা `:`-এর পর থেকে `handle()` method-এর অতিরিক্ত argument হিসেবে পাঠায়।

যেমন:

```php
Route::middleware('role:admin')->group(function () {
    //
});
```

এখানে:

```text
role
 ↓
Middleware alias
```

আর:

```text
admin
 ↓
Middleware parameter
 ↓
$roles
```

তাই তোমার method:

```php
public function handle(Request $request, Closure $next, ...$roles)
```

এর মধ্যে হবে:

```php
$roles = ['admin'];
```

### Multiple role হলে

```php
Route::middleware('role:admin,manager')->group(function () {
    //
});
```

তখন:

```php
$roles = ['admin', 'manager'];
```

অর্থাৎ Laravel comma-separated middleware parameters-গুলো আলাদা করে `...$roles` array-তে পাঠায়।

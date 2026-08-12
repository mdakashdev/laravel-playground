# Sprint 3 — Role & Permission (RBAC - Role-Based Access Control)

## Objective

Authentication-এর ওপর **Role & Permission system** তৈরি করা।

এই Sprint শেষে থাকবে:

```text
User
 └── Role
      └── Permissions
```

### Task List

```text
⬜ Task 1 — Install & Configure RBAC
⬜ Task 2 — Permission Seeder
⬜ Task 3 — Role Seeder
⬜ Task 4 — Assign Role to User
⬜ Task 5 — Role Middleware
⬜ Task 6 — Permission Middleware
⬜ Task 7 — Admin APIs
⬜ Task 8 — Feature Tests
⬜ Task 9 — Refactor
⬜ Task 10 — Cleanup
```

---

# Task 1 — Install & Configure RBAC

আমরা **Spatie Laravel Permission** ব্যবহার করব।

## Step 1 — Install

```bash
docker compose exec app composer require spatie/laravel-permission
```

---

## Step 2 — Publish

```bash
docker compose exec app php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

---

## Step 3 — Migrate

```bash
docker compose exec app php artisan migrate
```

---

## Step 4 — User Model

`App\Models\User`

`HasRoles` trait add করো:

```php
use Spatie\Permission\Traits\HasRoles;
```

তারপর:

```php
use HasRoles;
```

---

## Step 5 — Verify Tables

Database-এ এগুলো তৈরি হয়েছে কিনা check করো:

```text
roles
permissions
model_has_roles
model_has_permissions
role_has_permissions
```

---

## Step 6 — Clear Cache

```bash
docker compose exec app php artisan optimize:clear
```

---

## Step 7 — Verify

```bash
docker compose exec app php artisan tinker
```

তারপর: এটা database-এ কতগুলো Role আছে সেটা count করবে

```php
Spatie\Permission\Models\Role::count();
```

Expected: যদি result 0 হয় - তার মানে এখনো কোনো Role তৈরি করা হয়নি।

```text
0
```

তারপর: এটা database-এ কতগুলো Permission আছে সেটা count করবে।

```php
Spatie\Permission\Models\Permission::count();
```

Expected: যদি result 0 হয় - তার মানে এখনো কোনো Permission তৈরি করা হয়নি।

```text
0
```

Exit:

```php
exit
```

---

## Step 8 — Commit

```bash
git add .
git commit -m "feat(rbac): install and configure role permission system"
git push
```

---

# Task 2: Permission Seeder

## Goal

Application-এর সব permission database-এ seed করা।

### Step 1 — Seeder তৈরি

```bash
docker compose exec app php artisan make:seeder PermissionSeeder
```

### Step 2 — Permissions

`database/seeders/PermissionSeeder.php`-এ এই permissions তৈরি করো:

```text
users.view
users.create
users.update
users.delete

products.view
products.create
products.update
products.delete

orders.view
orders.create
orders.update
orders.delete
```

প্রতিটি permission-এর জন্য Spatie-এর `Permission` model ব্যবহার করবে।

**Duplicate permission তৈরি করা যাবে না।**

### Step 3 — Seeder Register

`DatabaseSeeder.php` থেকে `PermissionSeeder` call করো।

### Step 4 — Run

```bash
docker compose exec app php artisan db:seed --class=PermissionSeeder
```

তারপর verify:

```bash
docker compose exec app php artisan tinker
```

```php
Spatie\Permission\Models\Permission::count();
```

Expected:

```text
12
```

তারপর:

```php
Spatie\Permission\Models\Permission::pluck('name');
```

১২টি permission দেখতে হবে।

### Step 5 — Commit

```bash
git add .
git commit -m "feat(rbac): add permission seeder"
git push
```

---

# Task 3: Role Seeder

## Goal

Default application roles তৈরি করা এবং permissions assign করা।

### Step 1 — Seeder তৈরি

```bash
docker compose exec app php artisan make:seeder RoleSeeder
```

### Step 2 — Roles

এই ৩টি role তৈরি করো:

```text
admin
manager
customer
```

### Step 3 — Permission Assignment

```text
admin
→ সব 12 permissions

manager
→ users.view
→ users.update

→ products.view
→ products.create
→ products.update

→ orders.view
→ orders.update

customer
→ products.view
→ orders.view
→ orders.create
```

### Step 4 — Seeder Register

`DatabaseSeeder.php`-এ `RoleSeeder` call করো।

Order:

```text
PermissionSeeder
↓
RoleSeeder
```

### Step 5 — Run

```bash
docker compose exec app php artisan db:seed
```

### Step 6 — Verify

```bash
docker compose exec app php artisan tinker
```

```php
Spatie\Permission\Models\Role::with('permissions')->get();
```

Verify করো:

```text
admin     → 12 permissions
manager   → 8 permissions
customer  → 5 permissions
```

### Step 7 — Commit

```bash
git add .
git commit -m "feat(rbac): add role seeder"
git push
```
---

# Task 4: Assign Role to User

## Goal

Admin user তৈরি হলে তাকে `admin` role assign করা এবং authenticated user-এর role management তৈরি করা।

### Step 1 — Admin Seeder

```bash id="5f3n2x"
docker compose exec app php artisan make:seeder AdminUserSeeder
```

একটি default admin user তৈরি করো:

```text
name: Admin
email: admin@example.com
password: password
```

Password অবশ্যই `Hash::make()` দিয়ে store করবে।

---

### Step 2 — Assign Role

User create করার পর:

```php
$user->assignRole('admin');
```

Duplicate admin user তৈরি করবে না।

---

### Step 3 — Seeder Order

`DatabaseSeeder.php`:

```text id="x9g6s1"
PermissionSeeder
↓
RoleSeeder
↓
AdminUserSeeder
```

---

### Step 4 — Run

```bash id="fhv0o4"
docker compose exec app php artisan db:seed
```

---

### Step 5 — Verify

```bash id="f9z6kl"
docker compose exec app php artisan tinker
```

```php id="3a0u2v"
$user = App\Models\User::where('email', 'admin@example.com')->first();

$user->roles->pluck('name');
```

Expected:

```text
["admin"]
```

তারপর:

```php id="l6m7sm"
$user->getAllPermissions()->pluck('name');
```

Expected:

```text
12 permissions
```

---

### Step 6 — Test Login

Existing Login API দিয়ে:

```text
email: admin@example.com
password: password
```

Token পাওয়া উচিত।

---

### Step 7 — Commit

```bash id="d2z4qj"
git add .
git commit -m "feat(rbac): add admin user role assignment"
git push
```

# Task 5: Role Middleware

## Goal

শুধু নির্দিষ্ট role-এর user যেন protected endpoint access করতে পারে।

---

### Step 1 — Middleware তৈরি

```bash id="z7q0h1"
docker compose exec app php artisan make:middleware RoleMiddleware
```

---

### Step 2 — Middleware Logic

`RoleMiddleware`-এ:

```text id="g7x4v2"
User authenticated?
    ↓
No → 401
    ↓
Yes
    ↓
Required role আছে?
    ↓
No → 403
    ↓
Yes
    ↓
Request continue
```

Middleware multiple roles support করবে।

Example:

```text id="xqv8ps"
role:admin
```

অথবা:

```text id="3y5n0k"
role:admin,manager
```

---

### Step 3 — Middleware Register

Laravel 12-এর জন্য `bootstrap/app.php`-এ alias register করো:

```php id="4q2q5z"
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

Existing `withMiddleware()` থাকলে সেটার মধ্যে add করবে।

---

### Step 4 — Middleware Usage

একটি test route তৈরি করো:

```php id="6r7v2p"
Route::get('/admin/test', function () {
    return ApiResponse::success('Admin access granted.');
})->middleware(['auth:sanctum', 'role:admin']);
```

---

### Step 5 — Test

#### Admin

Login → token → endpoint call

```http
GET /api/v1/admin/test
Authorization: Bearer TOKEN
```

Expected:

```http
200
```

---

#### Customer

Customer token দিয়ে একই endpoint call।

Expected:

```http
403
```

---

#### Unauthenticated

Token ছাড়া call।

Expected:

```http
401
```

---

### Step 6 — Commit

```bash id="d7n3kp"
git add .
git commit -m "feat(rbac): add role middleware"
git push
```

---

# Task 6: Permission Middleware

## Goal

Role-এর পাশাপাশি **specific permission** অনুযায়ী endpoint protect করা।

---

### Step 1 — Middleware তৈরি

```bash
docker compose exec app php artisan make:middleware PermissionMiddleware
```

### Step 2 — Middleware Logic

Flow:

```text
User authenticated?
    ↓
No → 401
    ↓
Permission আছে?
    ↓
No → 403
    ↓
Yes → Continue
```

Multiple permissions support করবে:

```text
permission:users.view
```

এবং:

```text
permission:users.view,users.update
```

---

### Step 3 — Register Alias

`bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'permission' => \App\Http\Middleware\PermissionMiddleware::class,
    ]);
})
```

---

### Step 4 — Test Route

```php
Route::get('/users/test', function () {
    return ApiResponse::success('Permission granted.');
})->middleware([
    'auth:sanctum',
    'permission:users.view',
]);
```

---

### Step 5 — Test

#### Admin

`admin` user-এর `users.view` আছে।

Expected:

```http
200
```

#### Manager

`manager` user-এর `users.view` আছে।

Expected:

```http
200
```

#### Customer

`customer` user-এর `users.view` নেই।

Expected:

```http
403
```

#### Unauthenticated

Token ছাড়া:

```http
401
```

---

### Step 6 — Verify Multiple Permission Support

একটি route-এ:

```text
permission:users.view,users.update
```

দিয়ে verify করো।

---

### Step 7 — Commit

```bash
git add .
git commit -m "feat(rbac): add permission middleware"
git push
```
---

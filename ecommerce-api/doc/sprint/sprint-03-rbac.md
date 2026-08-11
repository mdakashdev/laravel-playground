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

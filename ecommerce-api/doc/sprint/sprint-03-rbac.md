# Sprint 3 — Role & Permission (RBAC)

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

তারপর:

```php
Spatie\Permission\Models\Role::count();
```

Expected:

```text
0
```

তারপর:

```php
Spatie\Permission\Models\Permission::count();
```

Expected:

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

# ✅ Task 1 Checklist

* [ ] Spatie Permission installed
* [ ] Configuration published
* [ ] Migration completed
* [ ] `HasRoles` added to User
* [ ] RBAC tables created
* [ ] Cache cleared
* [ ] Tinker verified
* [ ] Commit & Push

**শেষ হলে `Done` লিখবে।**

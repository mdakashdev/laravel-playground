# Sprint 1 — Authentication API

## Task 1 — Install & Configure Laravel Sanctum

### Goal

Laravel API authentication-এর foundation তৈরি করা।

---

## Step 1

Container-এ ঢুকো।

```bash
docker compose exec app bash
```

---

## Step 2

Sanctum install করো।

```bash
composer require laravel/sanctum
```

---

## Step 3

Sanctum publish করো।

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

---

## Step 4

Migration run করো।

```bash
php artisan migrate
```

Expected:

নতুন `personal_access_tokens` table তৈরি হবে।

---

## Step 5

`config/auth.php`-এ `guards` section verify করো।

`sanctum` guard **add করবে না**। Laravel 12-এর default configuration-ই থাকবে।

---

## Step 6

`app/Models/User.php`-এ `HasApiTokens` trait add করো।

```php
use Laravel\Sanctum\HasApiTokens;
```

এবং:

```php
use HasApiTokens;
```

---

## Step 7

Container থেকে বের হয়ে আসো।

```bash
exit
```

---

## ✅ Checklist

* [ ] Sanctum installed
* [ ] Sanctum migration published
* [ ] `personal_access_tokens` table created
* [ ] `HasApiTokens` added to `User` model
* [ ] No manual auth guard changes

---

## Commit

```bash
git add .
git commit -m "feat(auth): install Laravel Sanctum"
git push
```

---

শেষ হলে শুধু লিখবে:

```text
Done
```

তারপর **Sprint 1 → Task 2 (API Versioning & Authentication Routes)** শুরু করব।

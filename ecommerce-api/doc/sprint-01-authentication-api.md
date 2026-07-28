# Sprint 1 — Authentication API

# Task 1 — Install & Configure Laravel Sanctum

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

## Commit

```bash
git add .
git commit -m "feat(auth): install Laravel Sanctum"
git push
```

---

# Task 2: API Versioning

> **আজকের Goal:** Production-standard API structure তৈরি করা। কোনো authentication logic লিখব না।

---

## Step 1 — API Version Folder

নিচের folder তৈরি করো:

```text
app/
└── Http/
    └── Controllers/
        └── Api/
            └── V1/
```

---

## Step 2 — Auth Controller

Controller তৈরি করো।

```bash
docker compose exec app php artisan make:controller Api/V1/AuthController
```

Expected:

```text
app/Http/Controllers/Api/V1/AuthController.php
```

---

## Step 3 — API Route File

নতুন file তৈরি করো।

```text
routes/api_v1.php
```

---

## Step 4 — Route Registration

`bootstrap/app.php`-এ `api_v1.php` register করো।

Route prefix হবে:

```text
/api/v1
```

> **নিজে research করে implement করবে।**
> আমি code দেব না। এটা Laravel 12-এর routing structure বুঝার practical task।

---

## Step 5 — Test Route

`routes/api_v1.php`-এ একটি test route দাও।

Endpoint:

```text
GET /api/v1/ping
```

Response:

```json
{
    "message": "pong"
}
```

---

## Step 6 — Verify

Browser বা Postman-এ test করো:

```text
GET http://localhost:8000/api/v1/ping
```

Expected:

```json
{
    "message": "pong"
}
```

---

## Commit

```bash
git add .
git commit -m "feat(api): add v1 api versioning"
git push
```

---

# Sprint 1 — Task 3: API Response Standard

## Goal

আজ থেকে **সব API একই JSON format** return করবে।

---

### Step 1

নিচের folder তৈরি করো:

```text
app/Support
```

---

### Step 2

একটি class তৈরি করো:

```text
app/Support/ApiResponse.php
```

---

### Step 3

এই class-এর responsibility হবে:

* Success response
* Error response
* Message
* Data
* Errors
* HTTP Status Code

> **নিজে implement করবে।**

---

### Step 4

`GET /api/v1/ping` endpoint-কে এই response helper ব্যবহার করে return করো।

Expected response:

```json
{
    "success": true,
    "message": "pong",
    "data": null
}
```

---

### Step 5

Production rules

* ❌ `response()->json()` directly ব্যবহার করবে না Controller-এ।
* ✅ সব response `ApiResponse` class দিয়ে যাবে।

---

### Verify

```http
GET http://localhost:8000/api/v1/ping
```

Expected:

```json
{
    "success": true,
    "message": "pong",
    "data": null
}
```

---

### Commit

```bash
git add .
git commit -m "feat(api): add standard api response helper"
git push
```

---

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

# Task 3: API Response Standard

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



✅ **Review: Approved**

এখন পর্যন্ত architecture clean আছে।

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           └── V1/
└── Support/
    └── ApiResponse.php
```

এখন থেকে আমরা CRUD শুরু করব না। আগে production foundation শেষ করব।

---

# Task 4: Global Exception Handling

## Goal

Project-এর **সব exception** এক জায়গা থেকে handle হবে।

**Controller, Service, Repository কোথাও `try/catch` লিখবে না** (বিশেষ business case ছাড়া)।

---

## Step 1

Laravel 12-এ exception handling কোথায় configure হয় সেটা identify করো।

> Hint: `app/Exceptions/Handler.php` না-ও হতে পারে।

---

## Step 2

নিচের exception-গুলোর জন্য JSON response implement করো।

| Exception               | HTTP |
| ----------------------- | ---: |
| ValidationException     |  422 |
| AuthenticationException |  401 |
| AuthorizationException  |  403 |
| ModelNotFoundException  |  404 |
| NotFoundHttpException   |  404 |
| Throwable (fallback)    |  500 |

সব response `ApiResponse::error()` ব্যবহার করবে।

---

## Step 3

**Production rule**

API request হলে JSON return করবে।

Web request হলে Laravel-এর default behavior থাকবে।

---

## Step 4

Testing

নিচের route **temporary** add করো:

```text
GET /api/v1/error-test
```

Route-এর ভিতরে:

```
throw new Exception('Test Exception');
```

Expected Response:

```json
{
    "success": false,
    "message": "Internal Server Error",
    "data": null,
    "errors": null
}
```

Status:

```text
500
```

---

## Step 5

Testing শেষ হলে `error-test` route delete করে দিও।

Testing route production code-এ থাকবে না।

---

## Commit

```bash
git add .
git commit -m "feat(core): add global api exception handling"
git push
```

---

## 🔴 Change Request (Required)

`500` response-এ **raw exception message** return করবে না।

❌ এটা করবে না:

```json
{
  "message": "SQLSTATE[42S02]..."
}
```

✅ Production-এ:

```json
{
  "success": false,
  "message": "Internal Server Error",
  "data": null,
  "errors": null
}
```

Debug information শুধুমাত্র log-এ যাবে, response-এ নয়।

---


# Sprint 1 — Task 5: Authentication Database Design

> **আজ কোনো API লিখব না।** শুধু database design।

## Goal

Authentication-এর জন্য production-ready schema তৈরি করা।

---

### Step 1

`users` table review করো।

শুধু এই fields রাখবে:

* name
* email
* password
* email_verified_at
* remember_token
* timestamps

> Default Laravel migration-এর বাইরে **কিছু add করবে না**।

---

### Step 2

নতুন migration তৈরি করো:

```bash
docker compose exec app php artisan make:migration add_profile_fields_to_users_table --table=users
```

---

### Step 3

নিচের fields add করো:

| Column | Type                     |
| ------ | ------------------------ |
| uuid   | uuid (unique)            |
| phone  | string, nullable, unique |
| avatar | string, nullable         |
| status | boolean, default true    |

> **`is_active` নয়, `status` ব্যবহার করো।**

---

### Step 4

Migration run করো।

```bash
docker compose exec app php artisan migrate
```

---

### Step 5

Verify

```bash
docker compose exec app php artisan db:show
```

---

### Commit

```bash
git add .
git commit -m "feat(auth): extend users table"
git push
```

---

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

# Task 5: Authentication Database Design

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





## ✅ Review: **Pass (9/10)**

ভালো progress। Foundation ধীরে ধীরে production-ready হচ্ছে।

---

## Review Notes

### ✅ Good

* `uuid` ✔️
* `phone` nullable + unique ✔️
* `avatar` nullable ✔️
* `status` default `true` ✔️

---

## 🔴 Change Request (Required)

`uuid` **database default** দিয়ে generate করবে না।

আমরা পরে `UserObserver` দিয়ে create-এর সময় UUID generate করব।

**Rule:**

* Database → শুধু column define করবে
* Business logic → Observer

এটা maintain করব পুরো project-এ।

---

## 🔴 Change Request (Required)

`status`-এ magic number ব্যবহার করবে না।

পরে আমরা:

```php
UserStatus::ACTIVE
UserStatus::INACTIVE
```

বা Enum ব্যবহার করব।

এখন boolean ঠিক আছে, কিন্তু roadmap-এ এটা পরে refactor হবে।

---

# Task 6: User Model Preparation

> এখন আমরা `User` model production-ready করব। এখনও Register/Login লিখব না।

## Goal

`User` model clean এবং future-proof করা।

---

### Step 1

`fillable` update করো।

শুধু এই fields থাকবে:

```text
name
email
password
phone
avatar
status
uuid
```

---

### Step 2

`hidden` verify করো।

শুধু:

```text
password
remember_token
```

---

### Step 3

`casts` verify/update করো।

```text
email_verified_at => datetime
password => hashed
status => boolean
```

---

### Step 4

`HasApiTokens` trait আছে কিনা verify করো।

এবং Laravel-এর default traits ছাড়া অতিরিক্ত trait add করবে না।

---

### Step 5

Model constants বা business method **এখন add করবে না**।

এখন শুধু model preparation।

---

## Commit

```bash
git add .
git commit -m "refactor(auth): prepare user model"
git push
```
---

# Task 7: Registration Architecture

> **আজও Register API লিখব না।** শুধু architecture তৈরি করব।

## Goal

Production-standard folder structure তৈরি করা।

---

## Step 1

নিচের folders তৈরি করো:

```text
app/
├── Actions/
│   └── Auth/
├── Http/
│   ├── Requests/
│   │   └── Api/
│   │       └── V1/
│   │           └── Auth/
│   └── Resources/
│       └── Api/
│           └── V1/
├── Services/
│   └── Auth/
```

---

## Step 2

এই files তৈরি করো।

### Action

```bash
docker compose exec app php artisan make:class Actions/Auth/RegisterUserAction
```

### Service

```bash
docker compose exec app php artisan make:class Services/Auth/AuthService
```

### Form Request

```bash
docker compose exec app php artisan make:request Api/V1/Auth/RegisterRequest
```

### API Resource

```bash
docker compose exec app php artisan make:resource Api/V1/UserResource
```

---

## Step 3

`AuthController`-এ এখন **কোনো logic লিখবে না**।

শুধু empty methods তৈরি করো:

```text
register
login
logout
me
refresh
forgotPassword
resetPassword
verifyEmail
resendVerification
```

সব method আপাতত:

```php
return ApiResponse::success('Pending implementation');
```

---

## Step 4

কোনো route add করবে না।

শুধু architecture prepare করবে।

---

## Commit

```bash
git add .
git commit -m "chore(auth): prepare authentication architecture"
git push
```

---

# Task 8: Register Validation

## Goal

User registration-এর validation complete করা।

**আজ database-এ কিছু save হবে না।**

---

## Step 1

`RegisterRequest`-এ authorization update করো।

```php
public function authorize(): bool
{
    return true;
}
```

---

## Step 2

Validation rules implement করো।

| Field    | Rules                                        |
| -------- | -------------------------------------------- |
| name     | required, string, min:3, max:100             |
| email    | required, email, max:255, unique:users,email |
| password | required, confirmed, min:8, max:255          |
| phone    | nullable, string, unique:users,phone         |
| avatar   | prohibited                                   |

> **`avatar` registration-এ allow করবে না।**

---

## Step 3

Custom validation messages **add করবে না**।

Laravel default messages ব্যবহার করব।

---

## Step 4

Custom attributes **add করবে না**।

---

## Step 5

Temporary testing route add করো:

```
POST /api/v1/register-test
```

Controller থেকে শুধু:

```php
return ApiResponse::success(
    'Validation passed',
    $request->validated()
);
```

এখনো database save করবে না।

---

## Step 6

Postman দিয়ে test করো।

### Valid Request

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

Expected:

* **200 OK**
* `Validation passed`

---

### Invalid Request

```json
{
  "name": "",
  "email": "abc",
  "password": "123"
}
```

Expected:

* **422**
* Standard `ApiResponse` format

---

## Commit

```bash
git add .
git commit -m "feat(auth): implement registration validation"
git push
```

---

# Task 9: Register Action (Database Save)

> এখান থেকে user database-এ save হবে।

## Goal

Registration logic শুধু `RegisterUserAction`-এ থাকবে।

---

## Step 1

`RegisterUserAction`-এ একটি public method তৈরি করো:

```text
execute(array $data): User
```

Return type অবশ্যই `User` হবে।

---

## Step 2

এই method-এর দায়িত্ব:

* User create করা
* Password hash হবে (Laravel-এর hashed cast ব্যবহার করবে, `Hash::make()` করবে না)
* `status = true`
* `uuid` generate করা
* `avatar = null`

> **UUID এখন `Str::uuid()` দিয়ে generate করো।** পরে আমরা এটা `Observer`-এ move করব।

---

## Step 3

Action-এর ভিতরে **response return করবে না**।

শুধু `User` model return করবে।

---

## Step 4

Controller, Service, Route — কোনো পরিবর্তন করবে না।

আজ শুধু `RegisterUserAction` implement করবে।

---

## Commit

```bash
git add .
git commit -m "feat(auth): implement register user action"
git push
```

---

# Task 10: AuthService

## Goal

Controller আর Action-এর মাঝে orchestration/ অর্কেস্ট্রেশন layer তৈরি করা।

---

## Step 1

`AuthService`-এ constructor injection ব্যবহার করো।

Inject করবে:

```text
RegisterUserAction
```

---

## Step 2

একটি method implement করো:

```php
register(array $data): User
```

---

## Step 3

এই method-এর ভিতরে শুধু:

* `RegisterUserAction` call করবে।
* `User` return করবে।

**আর কোনো logic থাকবে না।**

---

## Step 4

Response return করবে না।

Exception catch করবে না।

Transaction ব্যবহার করবে না।

---

## Commit

```bash
git add .
git commit -m "feat(auth): implement auth service"
git push
```

---

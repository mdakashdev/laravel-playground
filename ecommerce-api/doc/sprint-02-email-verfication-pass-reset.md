# Sprint 2 — Email Verification & Password Reset

এখন থেকে Authentication module complete করব।

---

## Sprint 2 Task List

```text
Task 1  Email Verification Setup
Task 2  Send Verification Email
Task 3  Verify Email Endpoint
Task 4  Resend Verification Email
Task 5  Forgot Password
Task 6  Reset Password
Task 7  Password Reset Notification
Task 8  Queue Mail
Task 9  Authentication Feature Tests
Task 10 Refactor & Cleanup
```

---

# Task 1: Email Verification Setup

## Goal

User registration-এর পরে email verification support প্রস্তুত করা।

---

### Step 1

`User` model-এ verify করো যে:

```php
implements MustVerifyEmail
```

যোগ করা আছে।

---

### Step 2

`User` model-এ trait verify করো।

শুধু এগুলো থাকবে:

```text
HasApiTokens
HasFactory
Notifiable
```

কোনো extra trait add করবে না।

---

### Step 3

Registration-এর পর নতুন user-এর:

```text
email_verified_at = null
```

থাকছে কিনা verify করো।

---

### Step 4

Test

একজন নতুন user register করো।

Database-এ check করো:

```text
email_verified_at
```

Expected:

```text
NULL
```

---

### Step 5

কোনো email send করবে না।

কোনো notification লিখবে না।

আজ শুধু verification foundation প্রস্তুত করবে।

---

## Commit

```bash
git add .
git commit -m "feat(auth): prepare email verification foundation"
git push
```

---


# Task 2: Send Verification Email

## Goal

User registration-এর পরে verification email send হবে।

---

## Step 1

`AuthService::register()` update করো।

Flow হবে:

```text
Create User
    ↓
Send Email Verification
    ↓
Return User
```

---

## Step 2

User create হওয়ার পরে call করো:

```php
$user->sendEmailVerificationNotification();
```

---

## Step 3

`RegisterUserAction` পরিবর্তন করবে না।

Notification send করার logic শুধু `AuthService`-এ থাকবে।

---

## Step 4

Register API call করো।

নতুন user register করো।

---

## Step 5

Mailpit open করো:

```text
http://localhost:8025
```

Expected:

* Verification email এসেছে।
* Verification link আছে।

---

## Step 6

Database-এ verify করো:

```text
email_verified_at
```

Expected:

```text
NULL
```

Email click করার আগে verify হবে না।

---

## Commit

```bash
git add .
git commit -m "feat(auth): send email verification after registration"
git push
```

---

# Task 3: Verify Email Endpoint

## Goal

User verification link-এ click করলে email verified হবে।

---

### Step 1

Temporary route remove করো।

---

### Step 2

`AuthService`-এ method add করো:

```php
verifyEmail(EmailVerificationRequest $request): void
```

Responsibilities:

* Email verify করবে।
* যদি already verified হয়, কোনো error দিবে না।

---

### Step 3

`AuthController`

Method:

```php
verifyEmail(EmailVerificationRequest $request)
```

Flow:

```text
EmailVerificationRequest
        ↓
AuthService::verifyEmail()
        ↓
ApiResponse::success()
```

Response:

```json
{
  "success": true,
  "message": "Email verified successfully.",
  "data": null
}
```

---

### Step 4

Route

```http
GET /api/v1/email/verify/{id}/{hash}
```

Middleware:

```text
signed
```

Route name অবশ্যই:

```text
verification.verify
```

---

### Step 5

Testing

1. নতুন user register করো।
2. Mailpit থেকে verification link open করো।

Expected:

* **200 OK**
* `"Email verified successfully."`

---

### Step 6

Database verify করো।

`email_verified_at`

আগে:

```text
NULL
```

পরে:

```text
timestamp
```

---

## Commit

```bash
git add .
git commit -m "feat(auth): implement email verification endpoint"
git push
```

---

## 🚨 Roadmap Adjustment (Production API Standard)


```text
Register
    ↓
Send Verification Email
    ↓
User clicks link
    ↓
GET /email/verify/{id}/{hash}
    ↓
Find User
    ↓
Validate Hash
    ↓
markEmailAsVerified()
    ↓
JSON Response
```

এটা API-first architecture-এর জন্য standard।

নতুন implementation করব।

### Controller

`EmailVerificationRequest` বাদ।

```php
public function verifyEmail(Request $request, int $id, string $hash)
```

---

### Service

Method হবে:

```php
verifyEmail(int $id, string $hash): void
```

### ভিতরে

1. User find করবে।
2. Hash check করবে।
3. Already verified হলে skip করবে।
4. `markEmailAsVerified()` করবে।
5. Event fire হবে (Laravel automatically করে)।

## ❌ `EmailVerificationRequest` ব্যবহার করব না।

কারণ এটা session/web authentication-এর জন্য design করা।

আমরা API backend বানাচ্ছি।

দারুণ। এটাই আমাদের roadmap-এর **প্রথম adjustment**।

আমি আগে বলেছিলাম **"standard অনুযায়ী"** করব। এখানে Laravel-এর default standard (`EmailVerificationRequest`) আসলে **web application**-এর জন্য। কিন্তু আমরা **REST API backend** বানাচ্ছি, তাই API standard follow করব।

---

# Task 3 (Updated): Verify Email API

## Goal

Verification link-এ click করলে email verified হবে এবং JSON response return করবে।

---

## Step 1

নতুন Action তৈরি করো।

```bash
docker compose exec app php artisan make:class Actions/Auth/VerifyEmailAction
```

---

## Step 2

`VerifyEmailAction`

Method:

```php
execute(int $id, string $hash): void
```

Responsibilities (শুধু এগুলো):

1. User `id` দিয়ে find করবে।
2. User না পেলে `404` exception।
3. Hash verify করবে।
4. Hash invalid হলে `403` exception।
5. যদি already verified না হয়, `markEmailAsVerified()` call করবে।

> ❌ Response return করবে না।

---

## Step 3

`AuthService`

Method:

```php
verifyEmail(int $id, string $hash): void
```

এখানে শুধু `VerifyEmailAction` call করবে।

---

## Step 4

`AuthController`

Method:

```php
verifyEmail(Request $request, int $id, string $hash)
```

Flow:

```text
Route
    ↓
AuthController
    ↓
AuthService
    ↓
VerifyEmailAction
    ↓
ApiResponse::success()
```

Response:

```json
{
  "success": true,
  "message": "Email verified successfully.",
  "data": null
}
```

---

## Step 5

Route

```php
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');
```

⚠️ **`auth:sanctum` middleware থাকবে না।**

---

## Step 6

Hash verify করার জন্য Laravel-এর একই logic ব্যবহার করবে।

Hint:

```php
sha1($user->getEmailForVerification())
```

এবং route-এর `$hash` compare করবে।

---

## Step 7

Testing

### Test 1

* নতুন user register
* Mailpit থেকে link open

Expected:

* `200 OK`
* `"Email verified successfully."`

---

### Test 2

Database

আগে:

```text
email_verified_at = NULL
```

পরে:

```text
email_verified_at = timestamp
```

---

### Test 3

URL-এর hash পরিবর্তন করে open করো।

Expected:

```http
403 Forbidden
```

---

## Commit

```bash
git add .
git commit -m "feat(auth): implement api email verification"
git push
```

---



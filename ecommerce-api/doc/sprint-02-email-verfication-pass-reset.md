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

# Task 4: Resend Verification Email

## Goal

যে user এখনও verify করেনি, সে নতুন verification email request করতে পারবে।

---

## Step 1

Create Request

```bash
docker compose exec app php artisan make:request Api/V1/Auth/ResendVerificationEmailRequest
```

Rules:

```text
email => required|email
```

---

## Step 2

Create Action

```bash
docker compose exec app php artisan make:class Actions/Auth/ResendVerificationEmailAction
```

Method:

```php
execute(string $email): void
```

Responsibilities:

1. Email দিয়ে user খুঁজবে।
2. User না থাকলে `404` exception।
3. যদি already verified হয় → `400` exception।
4. `sendEmailVerificationNotification()` call করবে।

> ❌ Response return করবে না।

---

## Step 3

`AuthService`

Method:

```php
resendVerificationEmail(string $email): void
```

শুধু Action call করবে।

---

## Step 4

`AuthController`

Method:

```text
resendVerificationEmail()
```

Flow:

```text
ResendVerificationEmailRequest
            ↓
validated()
            ↓
AuthService
            ↓
ApiResponse::success()
```

Response:

```json
{
  "success": true,
  "message": "Verification email sent successfully.",
  "data": null
}
```

Status:

```http
200 OK
```

---

## Step 5

Route

```http
POST /api/v1/email/verification-notification
```

> **এই endpoint-এ `auth:sanctum` ব্যবহার করবে না।**
> Email address দিয়েই request হবে।

---

## Step 6

Testing

### Test 1

Unverified user-এর email দিয়ে request।

Expected:

* `200 OK`
* Mailpit-এ নতুন verification email।

### Test 2

Verified user-এর email।

Expected:

* `400 Bad Request`

### Test 3

Unknown email।

Expected:

* `404 Not Found`

---

## Commit

```bash
git add .
git commit -m "feat(auth): implement resend verification email api"
git push
```

---

# Task 5: Forgot Password

## Goal

User password reset link request করতে পারবে।

---

## Step 1

Create Request

```bash
docker compose exec app php artisan make:request Api/V1/Auth/ForgotPasswordRequest
```

Rules:

```text
email => required|email
```

---

## Step 2

Create Action

```bash
docker compose exec app php artisan make:class Actions/Auth/ForgotPasswordAction
```

Method:

```php
execute(string $email): void
```

Responsibilities:

1. Email exists কিনা check করবে।
2. Password reset link send করবে Laravel Password Broker ব্যবহার করে।
3. Failed হলে exception throw করবে।

> ❌ Response return করবে না।

---

## Step 3

`AuthService`

Method:

```php
forgotPassword(string $email): void
```

শুধু Action call করবে।

---

## Step 4

`AuthController`

Method:

```text
forgotPassword()
```

Flow:

```text
ForgotPasswordRequest
        ↓
validated()
        ↓
AuthService
        ↓
ApiResponse::success()
```

Response:

```json
{
  "success": true,
  "message": "Password reset link sent successfully.",
  "data": null
}
```

Status:

```http
200 OK
```

---

## Step 5

Route

```http
POST /api/v1/forgot-password
```

No authentication middleware.

---

## Step 6

Testing

### Test 1

Valid email

Expected:

* `200 OK`
* Mailpit-এ password reset email।

---

### Test 2

Unknown email

Expected:

* Appropriate error response (তোমার global exception handling অনুযায়ী consistent response আসবে)।

---

## Commit

```bash
git add .
git commit -m "feat(auth): implement forgot password api"
git push
```

---

## কেন এই route (password.reset) লাগে?

Flow হচ্ছে:

```
POST /forgot-password
        │
        ▼
Password::sendResetLink()
        │
        ▼
Laravel ResetPassword Notification
        │
        ▼
route('password.reset')
        │
        ▼
Mail এ URL তৈরি করে
```

URL বানানোর সময় route না পেয়ে exception দিচ্ছে।

---

# এখন দুইটা option আছে

## Option 1 (Temporary - Testing)

শুধু Mailpit-এ mail দেখতে চাইলে একটা dummy route add করো।

```php
Route::get('/reset-password/{token}', function (string $token) {
    return response()->json([
        'token' => $token,
    ]);
})->name('password.reset');
```

এতে mail চলে যাবে।

---

## Option 2 (Production Standard) ✅

Custom `ResetPassword` Notification বানাবে।

```bash
php artisan make:notification ResetPasswordNotification
```

তারপর:

```php
public function toMail($notifiable)
{
    $url = config('app.frontend_url')
        . '/reset-password?token='
        . $this->token
        . '&email='
        . urlencode($notifiable->email);

    return (new MailMessage)
        ->subject('Reset Password')
        ->line('Click below to reset your password.')
        ->action('Reset Password', $url);
}
```

`User` model-এ override করবে:

```php
public function sendPasswordResetNotification($token): void
{
    $this->notify(new ResetPasswordNotification($token));
}
```

---

## আমি কী recommend করছি?

যেহেতু তুমি **API-first authentication system** বানাচ্ছ, **Option 2**-ই সঠিক architecture।




# Task 5 (Updated): Password Reset Route Foundation

> `Password::sendResetLink()` internally `password.reset` route generate করতে চায়। কিন্তু আমরা এখনও সেই route বানাইনি।
> 
## Goal

Laravel password reset email যেন valid URL generate করতে পারে।

---

### Step 1

`AuthController`-এ নতুন method add করো।

```php
public function passwordResetPage(Request $request, string $token)
{
    return ApiResponse::success(
        'Password reset token is valid.',
        [
            'token' => $token,
            'email' => $request->query('email'),
        ]
    );
}
```

---

### Step 2

Route add করো।

```php
Route::get('/reset-password/{token}', [AuthController::class, 'passwordResetPage'])
    ->name('password.reset');
```

⚠️ এখানে **`auth:sanctum` থাকবে না।**

---

### Step 3

আবার Forgot Password API call করো।

Expected:

* Mailpit-এ password reset email আসবে।
* Email-এর link open করলে JSON response আসবে।

---

### Step 4

Commit

```bash
git add .
git commit -m "feat(auth): add password reset route foundation"
git push
```

---

## ✅ কেন এটা করছি?

Laravel-এর Password Broker **`password.reset` named route** খোঁজে। Route না থাকলে email generate করতে পারে না।

এখন route foundation তৈরি হলে পরের task-এ আমরা সেই token ব্যবহার করে **actual Reset Password API** implement করব।

---


# ask 6: Reset Password API

## Goal

User reset token ব্যবহার করে নতুন password সেট করতে পারবে।

> ⚠️ এই Task-এ **কোনো extra feature করবে না**। শুধু standard implementation।

---

## Step 1 — Create Request

```bash
docker compose exec app php artisan make:request Api/V1/Auth/ResetPasswordRequest
```

Rules

```text
token => required|string
email => required|email
password => required|string|min:8|confirmed
```

---

## Step 2 — Create Action

```bash
docker compose exec app php artisan make:class Actions/Auth/ResetPasswordAction
```

Method

```php
execute(array $data): void
```

Responsibilities

* Laravel Password Broker ব্যবহার করবে।
* Token verify করবে।
* Password update করবে।
* Remember token regenerate করবে।
* Success না হলে exception throw করবে।
* Response return করবে না।

---

## Step 3 — AuthService

Method

```php
resetPassword(array $data): void
```

Responsibilities

* শুধু `ResetPasswordAction` call করবে।

---

## Step 4 — AuthController

Method

```php
resetPassword(ResetPasswordRequest $request)
```

Flow

```text
ResetPasswordRequest
        ↓
validated()
        ↓
AuthService
        ↓
ApiResponse::success()
```

Response

```json
{
    "success": true,
    "message": "Password reset successfully.",
    "data": null
}
```

Status

```http
200 OK
```

---

## Step 5 — Route

```http
POST /api/v1/reset-password
```

No authentication middleware.

---

## Step 6 — Password Broker

`ResetPasswordAction`-এ Laravel Password Broker ব্যবহার করবে।

Success status:

```text
Password::PASSWORD_RESET
```

Failure:

Exception throw করবে।

---

## Step 7 — Password Update Callback

Password reset success হলে callback-এর ভিতরে:

* User password update করবে (`Hash::make()`)
* `remember_token` regenerate করবে

> **শুধু এই দুইটা কাজ।**

---

## Step 8 — Testing

### Test 1

Forgot Password API call করো।

Mailpit থেকে:

* token
* email

নিয়ে Reset Password API call করো।

Expected:

```http
200 OK
```

---

### Test 2

নতুন password দিয়ে Login API call করো।

Expected:

```http
200 OK
```

Old password দিয়ে login fail করবে।

---

### Test 3

Invalid token

Expected:

Consistent API error response।

---

### Test 4

Expired token (যদি test করতে চাও)

Expected:

Consistent API error response।

---

## Commit

```bash
git add .
git commit -m "feat(auth): implement reset password api"
git push
```

---


# Task 7: Queue Email

## Goal

Email (Verification + Forgot Password) **queue** দিয়ে পাঠানো, যাতে request দ্রুত response দেয় এবং email background-এ send হয়।

> ⚠️ এই Task-এ **শুধু email queue করবে**। Horizon, Supervisor বা production deployment এখন নয়।

---

# Step 1 — Queue Driver

`.env`

```dotenv
QUEUE_CONNECTION=database
```

---

# Step 2 — Queue Table

যদি queue table না থাকে:

```bash
docker compose exec app php artisan queue:table
docker compose exec app php artisan migrate
```

> যদি migration আগে থেকেই থাকে, তাহলে নতুন করে কিছু করার দরকার নেই।

---

# Step 3 — Queue Worker

নতুন terminal খুলে চালাও:

```bash
docker compose exec app php artisan queue:work
```

এই terminal খোলা রাখবে।

---

# Step 4 — Queue Verification Email

`App\Models\User`

`sendEmailVerificationNotification()` method override করো।

Laravel-এর `VerifyEmail` notification-কে queue-তে পাঠানোর মতো implementation করো।

> Requirement:
>
> * Notification অবশ্যই `ShouldQueue` implement করবে।
> * Existing email content পরিবর্তন করবে না।

---

# Step 5 — Queue Reset Password Email

Password Reset Notification-ও queue-তে পাঠাবে।

Requirement:

* Existing email content পরিবর্তন করবে না।
* শুধু queued notification ব্যবহার করবে।

---

# Step 6 — Testing

### Test 1

Register করো।

Expected:

* API সঙ্গে সঙ্গে `201`
* `jobs` table-এ job add হবে
* Queue worker email process করবে
* Mailpit-এ verification email আসবে

---

### Test 2

Forgot Password call করো।

Expected:

* API সঙ্গে সঙ্গে response
* Job create হবে
* Worker email send করবে
* Mailpit-এ reset email আসবে

---

### Test 3

Worker বন্ধ করো।

আবার Register করো।

Expected:

* Email সঙ্গে সঙ্গে যাবে না
* `jobs` table-এ pending থাকবে

---

### Test 4

Worker আবার চালাও।

Expected:

* Pending job process হবে
* Email Mailpit-এ পৌঁছাবে

---

# Commit

```bash
git add .
git commit -m "feat(auth): queue authentication emails"
git push
```

---

# Task 8: Refactor

## Goal

Authentication module clean, consistent এবং production-ready করা।

---

# Task 1 — Route Review

## Check

`routes/api_v1.php`

* [ ] Authentication routes একসাথে আছে।
* [ ] Public routes আগে।
* [ ] Protected routes (`auth:sanctum`) পরে।
* [ ] Route naming consistent।

শেষে structure এমন হবে:

```text
Public
    Register
    Login
    Forgot Password
    Reset Password
    Verify Email
    Resend Verification

Protected
    Logout
    Me
```

---

# Task 2 — Controller Review

Check every method.

* [ ] Controller-এ business logic নেই।
* [ ] শুধু Request → Service → ApiResponse।
* [ ] Validation Request class দিয়ে হচ্ছে।

---

# Task 3 — Service Review

Check.

* [ ] শুধু orchestration।
* [ ] Database query নেই।
* [ ] Response return করছে না।
* [ ] Exception swallow করছে না।

---

# Task 4 — Action Review

প্রতিটি Action check করো।

* [ ] Single Responsibility।
* [ ] এক Action = এক কাজ।
* [ ] Controller dependency নেই।
* [ ] Request dependency নেই।
* [ ] Response return নেই।

---

# Task 5 — ApiResponse Review

সব endpoint check করো।

Success format

```json
{
    "success": true,
    "message": "...",
    "data": {}
}
```

Error format

```json
{
    "success": false,
    "message": "...",
    "data": null,
    "errors": {}
}
```

সব endpoint একই format follow করছে।

---

# Task 6 — Exception Review

সব Action check করো।

* [ ] `abort()` যতটা সম্ভব remove করো।
* [ ] Meaningful exception throw করো।
* [ ] Global exception handler response return করবে।

> **Standard:** Business layer (`Action/Service`) HTTP response জানবে না।

---

# Task 7 — Naming Review

সব class check করো।

Examples

```
RegisterAction
LoginAction
ForgotPasswordAction
ResetPasswordAction
VerifyEmailAction
```

Request

```
LoginRequest
RegisterRequest
ForgotPasswordRequest
ResetPasswordRequest
```

সব naming consistent।

---

# Task 8 — Dependency Injection

Check.

* Constructor Injection everywhere.
* `new Class()` কোথাও নেই।

---

# Task 9 — Folder Structure

Expected

```text
app
 ├── Actions
 │    └── Auth
 │
 ├── Http
 │     ├── Controllers
 │     └── Requests
 │
 ├── Services
 │
 ├── Support
 │
 ├── Notifications
 │
 └── Models
```

---

# Task 10 — Dead Code Cleanup

Remove

* Temporary comments
* Unused imports
* Debug code
* `dd()`
* `dump()`
* `ray()`
* `logger()` (temporary)
* Commented code

---

# Manual Review

নিজেকে প্রশ্ন করো:

* একই logic দুই জায়গায় আছে?
* কোনো method খুব বড়?
* কোনো Action দুইটা কাজ করছে?
* কোনো Controller fat হয়ে গেছে?

যদি হয়, এখনই refactor করো।

---

# Final Testing

সব endpoint একবার test করো।

* Register
* Login
* Logout
* Me
* Verify Email
* Resend Verification
* Forgot Password
* Reset Password

সব successful হতে হবে।

---

# Commit

```bash
git add .
git commit -m "refactor(auth): improve authentication module structure"
git push
```

---

# Task 9: Feature Tests

## Goal

Authentication module-এর core API flow-এর **Feature Tests** লিখবে।

> ⚠️ Unit Test লিখবে না। শুধুমাত্র **Feature Tests**।

---

# Step 1 — Create Test

```bash
docker compose exec app php artisan make:test Feature/Api/V1/Auth/AuthTest
```

---

# Step 2 — Test Environment

Check:

* [ ] `RefreshDatabase` trait ব্যবহার করো।
* [ ] Test database automatically refresh হচ্ছে।

---

# Step 3 — Register Tests

লিখো।

### Test 1

```text
User can register successfully.
```

Check:

* Response `201`
* User database-এ আছে।

---

### Test 2

```text
Email must be unique.
```

Check:

* Response `422`

---

### Test 3

```text
Validation fails for invalid payload.
```

---

# Step 4 — Login Tests

### Test 1

```text
User can login with valid credentials.
```

Check:

* `200`
* Token exists।

---

### Test 2

```text
Invalid password.
```

Expected

```http
401
```

---

### Test 3

```text
Unknown email.
```

Expected

```http
401
```

---

# Step 5 — Protected Route Tests

### Me Endpoint

Authenticated user

Expected

```http
200
```

---

Unauthenticated

Expected

```http
401
```

---

### Logout

Authenticated

Expected

```http
200
```

Token deleted।

---

# Step 6 — Email Verification Tests

### Resend Verification

Unverified

Expected

```http
200
```

---

Verified

Expected

```http
400
```

---

# Step 7 — Forgot Password Tests

Valid email

Expected

```http
200
```

---

Unknown email

Expected

```http
404
```

---

# Step 8 — Reset Password Tests

Valid token

Expected

```http
200
```

Password updated।

---

Invalid token

Expected

```http
400
```

---

# Step 9 — Response Structure

সব endpoint check করো।

Success

```json
{
    "success": true,
    "message": "...",
    "data": ...
}
```

Error

```json
{
    "success": false,
    "message": "...",
    "errors": ...
}
```

সব test-এ response structure assert করবে।

---

# Step 10 — Run Tests

```bash
docker compose exec app php artisan test
```

সব test pass হতে হবে।

---

# Commit

```bash
git add .
git commit -m "test(auth): add authentication feature tests"
git push
```

---

### একটি গুরুত্বপূর্ণ নিয়ম (এখন থেকে)

Feature Test লেখার সময়:

* **Happy Path** অবশ্যই থাকবে।
* **Validation Failure** অবশ্যই থাকবে।
* **Unauthorized Case** (যেখানে প্রযোজ্য) অবশ্যই থাকবে।
* **Response Structure Assertion** প্রত্যেক endpoint-এ থাকবে।

এগুলো আমাদের পরবর্তী সব Sprint-এও একইভাবে অনুসরণ করা হবে।

# Task 10: Cleanup

## Goal

Authentication module release-ready করা। নতুন feature যোগ করা যাবে না।

---

# Task 1 — Remove Debug Code

পুরো project search করো এবং remove করো:

```text
dd(
dump(
ray(
logger(
var_dump(
print_r(
```

---

# Task 2 — Remove Dead Code

Remove:

* Unused methods
* Commented code
* Temporary routes
* Temporary helper methods
* Unused imports

---

# Task 3 — Route Review

Check:

* [ ] Public routes grouped
* [ ] Protected routes grouped
* [ ] Route names consistent
* [ ] Prefix consistent (`api/v1`)

---

# Task 4 — Request Review

সব Request class check করো।

* Validation rules clean
* Custom messages (যদি দরকার হয়)
* Unused imports remove

---

# Task 5 — Controller Review

সব Controller method check করো।

* Business logic নেই
* Response একই format
* Naming consistent

---

# Task 6 — Service Review

Check:

* One public method = one use case
* No duplicated logic
* Constructor Injection everywhere

---

# Task 7 — Action Review

সব Action check করো।

* Single Responsibility
* No Response
* No Request dependency
* No Controller dependency

---

# Task 8 — Code Style

Run:

```bash
docker compose exec app ./vendor/bin/pint
```

সব formatting fix করো।

---

# Task 9 — Final Test

Run:

```bash
docker compose exec app php artisan test
```

Expected:

```text
PASS
```

তারপর manually test করো:

* Register
* Login
* Logout
* Me
* Verify Email
* Resend Verification
* Forgot Password
* Reset Password

সব endpoint success হওয়া উচিত।

---

# Task 10 — Documentation Update

`README.md` update করো।

Minimum sections:

```text
Project Overview
Requirements
Installation
Docker Setup
Environment Variables
Run Project
Run Queue Worker
Run Tests
API Base URL
Authentication Flow
```

---

# Commit

```bash
git add .
git commit -m "chore(auth): cleanup authentication module"
git push
```

---

এটাই একটি production-quality authentication module-এর শক্ত ভিত্তি। Sprint 3-এ আমরা **Role & Permission (RBAC)** শুরু করব।

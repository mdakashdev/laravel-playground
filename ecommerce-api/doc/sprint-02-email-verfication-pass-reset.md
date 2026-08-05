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

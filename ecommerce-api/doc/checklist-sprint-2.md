# Sprint 2 — Email Verification & Password Reset

## Sprint 2 Progress

```text
✅ Task 1  Email Verification Foundation
✅ Task 2  Send Verification Email
✅ Task 3  Verify Email Endpoint
⬜ Task 4  Resend Verification Email
⬜ Task 5  Forgot Password
⬜ Task 6  Reset Password
⬜ Task 7  Queue Mail
⬜ Task 8  Feature Tests
⬜ Task 9  Refactor
⬜ Task 10 Cleanup
```

# ✅ Checklist

## Task 1: Email Verification Setup

* [ ] `MustVerifyEmail` implemented
* [ ] Traits verified
* [ ] `email_verified_at` remains `NULL`
* [ ] New user tested
* [ ] Commit & Push

## Task 2: Send Verification Email

* [ ] `AuthService` updated
* [ ] `sendEmailVerificationNotification()` called
* [ ] Email received in Mailpit
* [ ] Verification link exists
* [ ] `email_verified_at` still `NULL`
* [ ] Commit & Push

## Task 3: Verify Email Endpoint

* [ ] Temporary route removed
* [ ] `AuthService::verifyEmail()`
* [ ] `AuthController::verifyEmail()`
* [ ] Named route `verification.verify`
* [ ] Email verified
* [ ] `email_verified_at` updated
* [ ] Commit & Push

## Task 3 (Updated): Verify Email API

* [ ] `VerifyEmailAction`
* [ ] Hash validation
* [ ] `markEmailAsVerified()`
* [ ] `AuthService::verifyEmail()`
* [ ] Controller updated
* [ ] Route without `auth:sanctum`
* [ ] Success test
* [ ] Invalid hash test
* [ ] Commit & Push

## Task 4: Resend Verification Email

* [ ] `ResendVerificationEmailRequest`
* [ ] `ResendVerificationEmailAction`
* [ ] `AuthService::resendVerificationEmail()`
* [ ] `AuthController`
* [ ] Route added
* [ ] Mailpit test
* [ ] Verified user blocked
* [ ] Unknown email handled
* [ ] Commit & Push



# Draft

- built-in Email Verification flow ব্যবহার করতে পারব। se jonno MustVerifyEmail use in user model and trait check and db te check email_verified_at
- mail pathanor jonno - after user create - sendEmailNotification method use korechi just , then akta temp endpoint use korechi
* Register-এর পর email trigger হচ্ছে ✔️
* Mailpit-এ email এসেছে ✔️
* Verification link generate হয়েছে ✔️
- genereate email er jonno - route lagbe- sei router er akta rule ache - middleware signed and route-name use korte hobe `verification.verify`
  - age temp akta use kortechilam, now nije authController a method use korlam aar serviec use krlam bash

- got one error, error detect form log file - Call to a member function getKey() on null `EmailVerificationRequest.php:18`

`EmailVerificationRequest` internally ধরে নেয় যে user authenticated (`web` guard)।
 কিন্তু আমরা **API + Sanctum Token Authentication** করছি। Mailpit-এর verification link browser থেকে open হলে কোনো Bearer Token যায় না। তাই user authenticate হয় না।

---

task4: `Resend Verification Email` endpoint-এর উদ্দেশ্য হলো: **যে user registration করেছে কিন্তু এখনো email verify করেনি, সে আবার verification email পেতে পারবে।**

অনেক কারণে প্রথম email ব্যবহার করা নাও হতে পারে: Email spam folder-এ চলে গেছে, User email miss করেছে, Verification link expire হয়ে গেছে , User আবার email চায়

তাই একটি API রাখা হয়: POST /api/v1/email/verification-notification

যেখানে user শুধু তার email পাঠাবে: "email": "user@example.com" 

resendVerificationEmail er jonno, akta route define korlam then controller - service - action 

```text
then akta excepton handle korecilam sekhane `NotFoundHttpException` er jonno, application a jekono jaigai NotFoundHttpException emon erro asle
sei message ta lagbe, tar jonno HttpException a message and code print korte hobe.
```

---



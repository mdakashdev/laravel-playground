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


# Draft

- built-in Email Verification flow ব্যবহার করতে পারব। se jonno MustVerifyEmail use in user model and trait check and db te check email_verified_at
- mail pathanor jonno - after user create - sendEmailNotification method use korechi just , then akta temp endpoint use korechi
* Register-এর পর email trigger হচ্ছে ✔️
* Mailpit-এ email এসেছে ✔️
* Verification link generate হয়েছে ✔️
- genereate email er jonno - route lagbe- sei router er akta rule ache - middleware signed and route-name use korte hobe `verification.verify`
  - age temp akta use kortechilam, now nije authController a method use korlam aar serviec use krlam bash

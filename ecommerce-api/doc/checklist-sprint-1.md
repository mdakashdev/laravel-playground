# Sprint - 1 Status

- ✅ Install & Configure Laravel Sanctum
- ✅ API Versioning
- ✅ API Response Standard
  - সব API একই JSON format return করবে।
- ✅ Global Exception Handling
  - Project-এর সব exception এক জায়গা থেকে handle হবে।
- 🐝 Authentication Database Design
  - Authentication-এর জন্য production-ready schema তৈরি করা।


# Key Point

Task - 4: Global Exception Handling
- নতুন migration তৈরি
- Migration run করো।
- db check koro - php artisan db:show
- docker file change kore, rebuild dite perechi

```text
akta existing table a, single migration diye multiple field add kora jai.
table check korar jonno - php artisan db:table users
```

---

Task - 4: Global Exception Handling 
- Project-এর সব exception এক জায়গা থেকে handle হবে।
- Rules: **Controller, Service, Repository কোথাও `try/catch` লিখবে না**
- render() – custom HTTP response rendering er jonno
- Production rule
  - API request হলে JSON return করবে।
  - Web request হলে Laravel-এর default behavior থাকবে।

```text
tarmane, akta apiResponse create kore, sekhan theke success and error both handle kora.
Exception file ta ke alada kora. jeno code ta freash hoi
eita to api/ hole error gulo handle korbę, aar jodi web er error hoi tahole to laravel default handle korbe right -  হ্যাঁ, একদম ঠিক।
```

```note
`500` response-এ **raw exception message** return করবে না। Debug information শুধুমাত্র log-এ যাবে, response-এ নয়।
কারণ raw exception message দিলে database structure, table name, SQL query, file path ইত্যাদি leak হতে পারে।
এতে attacker অনেক তথ্য পেয়ে যায়।
```

---

Task-3: API Response Standard
- সব API একই JSON format** return করবে।

```text
ami standard success, error response banate pari, and use kote pari
sei jonno ami static korechi, aar response method gulo likhar jonno AI er help nite pari.
rules hocche- response()->json() directly controller a use kora jabe na, ApiResponse use korte hobe
standard api response helper banalam.
```
---

Task-2: API Versioning
- Production-standard API structure

```text
api version folder create kora, AuthController crate kora
api route create and registration kora. and implement test route - ping - "pong"
test using - curl, browser and postman
```
---

Task-1: Install & Configure Laravel Sanctum
- Laravel API authentication-এর foundation তৈরি করা।

```text
Inside container a, sanctum install korbo, publish, migrate 
HasApiTokens model a add koro, exit from container. 
```

---


# ✅ Checklist

## Task 1 — Install & Configure Laravel Sanctum

* [ ] Sanctum installed
* [ ] Sanctum migration published
* [ ] `personal_access_tokens` table created
* [ ] `HasApiTokens` added to `User` model
* [ ] No manual auth guard changes


## Task 2: API Versioning

* [ ] `Api/V1` folder
* [ ] `AuthController`
* [ ] `routes/api_v1.php`
* [ ] `/api/v1` prefix
* [ ] `/api/v1/ping` works
* [ ] Commit & Push

## Task 3: API Response Standard

* [ ] `ApiResponse` class
* [ ] Standard success response
* [ ] Standard error response
* [ ] Ping endpoint updated
* [ ] Commit & Push

## Task 4: Global Exception Handling

* [ ] Global exception handling configured
* [ ] API returns standard JSON
* [ ] Web routes keep default behavior
* [ ] `error-test` verified
* [ ] Temporary route removed
* [ ] Commit & Push

## Task 5: Authentication Database Design

* [ ] Migration created
* [ ] `uuid`
* [ ] `phone`
* [ ] `avatar`
* [ ] `status`
* [ ] Migration executed
* [ ] Commit & Push

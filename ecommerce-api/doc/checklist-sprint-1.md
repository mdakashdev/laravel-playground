# 🎯 Sprint 1 Completed

## Features Completed

* ✅ API Versioning
* ✅ Standard API Response
* ✅ Global Exception Handling
* ✅ User Model
* ✅ Register API
* ✅ Login API
* ✅ Me API
* ✅ Logout API
* ✅ Sanctum Authentication
* ✅ Clean Architecture

# Sprint - 1 Status

- ✅ Install & Configure Laravel Sanctum
- ✅ API Versioning
- ✅ API Response Standard
  - সব API একই JSON format return করবে।
- ✅ Global Exception Handling
  - Project-এর সব exception এক জায়গা থেকে handle হবে।
- ✅ Authentication Database Design
  - Authentication-এর জন্য production-ready schema তৈরি করা।
- ✅ User Model Preparation
  - `User` model clean এবং future-proof করা।
- ✅ Registration Architecture
  -Production-standard folder structure তৈরি করা।
- ✅ Register Validation
- ✅ Register Action (Database Save)
  - `RegisterUserAction`-এ একটি public method তৈরি করো
- ✅ AuthService
  - `AuthService`-এ constructor injection ব্যবহার করো। Inject করবে: RegisterUserAction
  - ami jei dependency (RegisterUserAction) inject korbo setar jonno akta service create korte hobe. se service er through te inject hobe
  - Controller আর Action-এর মাঝে orchestration/ অর্কেস্ট্রেশন layer তৈরি করা।
- ✅ Complete Register API
  - আজ প্রথম production-ready endpoint complete করব। শেষে user database-এ save হবে এবং `UserResource` return করবে।
- ✅ Task 12: Login API
  - Email + Password দিয়ে login করে **Sanctum Personal Access Token** return করবে।
- Get Authenticated User (`/me`)
  - Authenticated user-এর profile return করবে।
- Logout API
  -Authenticated user-এর current access token revoke করা।


✅ Task 1  Sanctum
✅ Task 2  API Versioning
✅ Task 3  API Response
✅ Task 4  Global Exception
✅ Task 5  User Database
✅ Task 6  User Model
✅ Task 7  Authentication Architecture
✅ Task 8  Registration Validation
✅ Task 9  RegisterUserAction
✅ Task 10 AuthService
✅ Task 11 Register API
✅ Task 12 Login API
✅ Task 13 Me API
✅ Task 14 Logout API


> তুমি এখন production-style Authentication module-এর foundation তৈরি করে ফেলেছো।


# Key Point

Task - 14:

Authenticated user-এর current access token revoke করা।

amara logout korbo, so token obossoi revoke kore deya,
logout er somai o token diye in korte hobe and middleware use korte hobe, karon ami toke revoke korte cai.

ami age route er endpoint a middleware auth:sanctum use korechilam for single thinking
but jokhon same middleware multiple endpoint a lagbe tokhon eita group a niye aste hobe - route group

Task - 13:

Authenticated user-এর profile return করবে।
obossoi auth:sanctum middleware use korbo.

jehetu authentication hote hobe, ta /me endpont er sathe login kora user er token lagbe ja postman a pass korte hobe

tarmane age login korte hobe tahole token pabo, then sei token niye authenticated endpoint call korte hobe

note: laravel Request a login user er data pauwa jai. method user()


Task - 12: 

Email + Password দিয়ে login করে **Sanctum Personal Access Token** return করবে।

Create Login Request, and validation rules set for email and pass
create LoginUserAction action and execute method
authservice a loginUserAction inject kore, method create kora.
AuthController a implemet kore login ke call kora.
Flow: LoginRequest -> validated() -> AuthService::login() -> UserResource -> ApiResponse
Expected: 200, User, Token

Wrong Password and Unknown Email er jonno =  401 Unauthorized ,Standard `ApiResponse`

note: change-required status false check korechi.



Task - 11:

আজ প্রথম production-ready endpoint complete করব। শেষে user database-এ save হবে এবং `UserResource` return করবে।

Step 1 — `UserResource`, `toArray()` implement করো।

শুধু এই fields return করবে: id, uuid, name, email, phone etc

controller a implement flow :

```
RegisterRequest
        ↓
validated()
        ↓
AuthService::register()
        ↓
UserResource
        ↓
ApiResponse::success()
```

Duplicate Email Test , একই email দিয়ে আবার request পাঠাও। Expected: **422*** Standard `ApiResponse`

- HTTP Status Register success হলে অবশ্যই: 201 Created 200` নয়।

Task - 10:

Controller আর Action-এর মাঝে orchestration/ অর্কেস্ট্রেশন layer তৈরি করা।

আগে আমি `Service` এবং `Action` আলাদা করে করাচ্ছি,
কারণ architecture establish করতে চাই। কিন্তু যেসব feature ছোট (যেমন Login, Logout), সেখানে unnecessary abstraction করব না।

**Rule (এখন থেকে):**

* ✅ Complex business operation → `Service + Action`
* ✅ Simple operation → `Service` only


Task - 9:

Registration logic শুধু `RegisterUserAction`-এ থাকবে।

target hocche - `RegisterUserAction`-এ একটি public method তৈরি করো

`RegisterUserAction`-এ একটি public method তৈরি করো: execute(array $data): User
 Return type অবশ্যই `User` হবে।

এই method-এর দায়িত্ব:
* User create করা , Password hash হবে (Laravel-এর hashed cast ব্যবহার করবে, `Hash::make()` করবে না)
* `status = true`, `uuid` generate করা, `avatar = null`
> **UUID এখন `Str::uuid()` দিয়ে generate করো।** পরে আমরা এটা `Observer`-এ move করব।

Action-এর ভিতরে **response return করবে না**। শুধু `User` model return করবে।


Task - 8:

User registration-এর validation complete করা।

route create korlam `register-test` then controller register call korlam
controller theke RegisterRequest ta theke validaton check kortechi

```text
note: postman diye check korlam, validation pass and validation failed
2 ta tei apiResponse theke success and error message dekhane, ja ami kore rakchilam; seta test korlam.

Flow - hocce : pass or fail -> but respnse from apiResponse custom file.
```



Task -7: 
**আজও Register API লিখব না।** শুধু architecture তৈরি করব।
Production-standard folder structure create korbo - inside app
Actions/Auth/ ; Http/Requests/Api/V1/Auth/ ; Http/Resources/Api/V1/ ; 
Services/Auth/ ; Http/Controllers/Api/V1/

- create empty method

note: request and resource default vabe, http er vitore hoi, but ami folder structure customize kore niyechi.
aar sei folder a request and resource file baniyechi jar command gulo - artisan-command.md file a ache


আমাদের flow সবসময় হবে:

```
Route
    ↓
Controller
    ↓
Form Request
    ↓
Service
    ↓
Action
    ↓
Model
    ↓
Resource
    ↓
ApiResponse
```


Task - 6: 

এখন আমরা `User` model production-ready করব। `User` model clean এবং future-proof করা।
field `fillable` korbo
jei field gulo dekhate cai na, seigulo modela a - hidden a assign kora.
cast korte hobe 2/1 field like- status, datetime etc


Task - 5:


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


## Task 6: User Model Preparation

* [ ] `fillable` updated
* [ ] `hidden` verified
* [ ] `casts` updated
* [ ] `HasApiTokens` verified
* [ ] Commit & Push

## Task 7: Registration Architecture

* [ ] Folder structure
* [ ] `RegisterUserAction`
* [ ] `AuthService`
* [ ] `RegisterRequest`
* [ ] `UserResource`
* [ ] Empty controller methods
* [ ] Commit & Push

## Task 8: Register Validation

* [ ] `authorize()` returns `true`
* [ ] Validation rules implemented
* [ ] No custom messages
* [ ] No custom attributes
* [ ] Test route works
* [ ] Validation errors follow `ApiResponse`
* [ ] Commit & Push

## Task 9: Register Action (Database Save)

* [ ] `execute(array $data): User`
* [ ] User created
* [ ] `uuid` generated
* [ ] Password uses model cast
* [ ] Returns `User`
* [ ] No response logic
* [ ] Commit & Push

## Task 10: AuthService

* [ ] Constructor Injection
* [ ] `register(array $data): User`
* [ ] Calls `RegisterUserAction`
* [ ] Returns `User`
* [ ] No response
* [ ] No try/catch
* [ ] No transaction
* [ ] Commit & Push

# Task 11: Complete Register API

* [ ] `UserResource` implemented
* [ ] `AuthController::register()` implemented
* [ ] `/api/v1/register` works
* [ ] User saved
* [ ] HTTP 201 returned
* [ ] Duplicate email returns 422
* [ ] Commit & Push

# Task 12: Login API


* [ ] `LoginRequest`
* [ ] `LoginUserAction`
* [ ] `AuthService::login()`
* [ ] Token created
* [ ] `POST /login`
* [ ] 200 Success
* [ ] 401 Invalid credentials
* [ ] Commit & Push


# Task 13: Get Authenticated User (`/me`)

* [ ] `/me` route
* [ ] `auth:sanctum` middleware
* [ ] `AuthService::me()`
* [ ] `AuthController::me()`
* [ ] `UserResource`
* [ ] 401 without token
* [ ] 200 with token
* [ ] Commit & Push

## Task 14 Logout API

* [ ] `/logout` route
* [ ] `auth:sanctum`
* [ ] `AuthService::logout()`
* [ ] Current token revoked
* [ ] `200 OK`
* [ ] Old token invalid
* [ ] Commit & Push

# Basic Understanding

Role based permission

amra kono user by user permission dibo na, amra permission dibo akta `role` er upor.

tar jonno amra `role based` boli.

tahole 1 ta role jei category te porbe, sei categorir sokol user ke amara sei role assign kore dite pari.

like - 1 ta `role: admin`
ei role a permission dibo - `user create, update and delete` korte parbe,

then jei koijon user ke amra ei permission gulo dite cai sei user gulo ke `admin` role ta assign kore dibo.

so ei kajta korte hole amader dorkar; 5 ta step

1. role
2. permission
3. one role many permissions
4. role assign to users
5. endpoint ke permission / role assign kora using middleware

> RBAC complete korte 5 ta step lage.

## Flow:

- তুমি যখন: `$user->assignRole('Admin'); করো`, তুমি endpoint protection করছো না। তুমি বলছো: এই user কোন permissions পাবে?

- অন্যদিকে: `->middleware('permission:users.view')` বলছে: এই endpoint ব্যবহার করতে কোন permission লাগবে?

অর্থাৎ:
ROLE
↓
কোন permission user পাবে?


MIDDLEWARE
↓
কোন permission endpoint-এর জন্য required?

---

RBAC use korar jonno amra laravel er packege use korbo - `spatie/laravel-permission`

Step
1. package install
2. Table
2. seeder
3. `HasRoles` trait add korbo. model a add korbo tahole sob relation er data gulo automatic peye jabe.
4. RoleMiddleware: নির্দিষ্ট role-এর user jeno sei endpoint access korte pare. seta check korbe, endpoint theke middlewar pahtbo seita 
    middleware dhorar jonno - spreed operator use korechi.
5. PermissionMiddleware: same oi user er niddisto permission ache kina check korbe. permission middleware pathaleo, 
   sei age role check kore then permision bec. eita role based aar amra kono user ke permision add kori ni, aar eita kore dei
    spatie er hasRole trait.
6. both middleware register
7. test korte hobe, alada alada user ke, konta success hocche aar konta fail. user - A, ke admin role diyechi, but user - B ke diye test korbo.
8. amar API diye role assign korbo, ja amra seeder diye korechilam.
    akta user ke role assign korte hole - api diye role nite hobe (admin/customer/manager) etc 
    sei jonno akta `request fil`e lagbe validate korar jonno
    then akta `action file` lagbe sync korar jonno, amra ager thakle replace kore dibo tai - `syncRoles` use korbo

## manually thinking

### Role

- prothome cinta korte hobe, amader ki ki role ache / thakbe / rakhbo
- Example: admin, customer, manager, hr, etc

Roles table: 
```
+----+----------+------------+---------------------+---------------------+
| id | name     | guard_name | created_at          | updated_at          |
+----+----------+------------+---------------------+---------------------+
|  1 | admin    | web        | 2026-08-11 17:09:58 | 2026-08-11 17:09:58 |
|  2 | manager  | web        | 2026-08-11 17:09:58 | 2026-08-11 17:09:58 |
|  3 | customer | web        | 2026-08-11 17:09:58 | 2026-08-11 17:09:58 |
+----+----------+------------+-----
```

next role a ki ki permission dibo, seta cinta korte hobe, amra jodi organized vabe cinta kori tahole amra
resource or model onujai think korte pari. so amader akta permission table lagbe jekhene amra permssion gulo rakhbo.

### Permission

- second think amader permission name gulo lagbe
- conventions follow korle - resource.action = users.view, users.create, users.update, users.delete

Permissions table:
```
+----+-----------------+------------+---------------------+---------------------+
| id | name            | guard_name | created_at          | updated_at          |
+----+-----------------+------------+---------------------+---------------------+
|  1 | users.view      | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  2 | users.create    | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  3 | users.update    | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  4 | users.delete    | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  5 | products.view   | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  6 | products.create | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  7 | products.update | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  8 | products.delete | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  9 | orders.view     | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
| 10 | orders.create   | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
| 11 | orders.update   | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
| 12 | orders.delete   | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
+----+-----------------+------------+---------------------+---------------------+

```

tahole amra role, and permission pelam, next kaj hocche, role er upor permission deya, so sohojei amra bujte parchi 
1 ta role er upor akadhik permission ser korte hobe or kora jabe. tai one role has many permissions

### Permission set on role

- role er upor permission set korar jonno, amader akta table lagbe 
- jehetu `one role has many permssions` so,

role_has_permissions
```
+---------------+---------+
| permission_id | role_id |
+---------------+---------+
|             1 |       1 |
|             2 |       1 |
|             3 |       1 |
|             4 |       1 |
|             5 |       1 |
|             6 |       1 |
|             7 |       1 |
|             8 |       1 |
|             9 |       1 |
|            10 |       1 |
|            11 |       1 |
|            12 |       1 |
+---------------+---------+
```

### role assign to users

now, amader kahce role based permission ache, so amra easily 1 ta role ke amader desire user ke assign kore dite pari.

model_has_roles table:
```
+---------+-----------------+----------+
| role_id | model_type      | model_id |
+---------+-----------------+----------+
|       1 | App\Models\User |       18 |
|       2 | App\Models\User |       18 |
|       3 | App\Models\User |       21 |
+---------+-----------------+----------+
```

note: amra caile ekjon user ke sorasori permission o set korte pari, but korbo na bec, amara role based korteci.

### endpoint permission / role assign kora.

ami user er permission set korechi role assign er maddhome - like `admin` role user.view, create, update and delete 

/admin/users , ei endpoint ta kara dekte parbe seta to kothao boli ni, sei jonno ekhane permission set korte hobe like

Route::get('/admin/users', [AdminController::class, 'index'])->middleware('permission:users.view');

- mane ami bollam, jei jei user er `users.view` permission ache tara dekte parbe.
- jodi role:admin ditam tahole jei jei user er admin role ache tara dekte peto, but permssion:users.view diyeche jeno
- onek role a user:view assign thakte pare je somosto role ei users.view permission deya ache tara sobai dekte parbe
- aar eita atkano hoi - middleware diye

## laravel package thinking 

amra upore jei cinta gulo korlam, sei table gulo ready korte hole migration likhte hobe , amra jodi 
`laravel spatie package` use kori tahole sei gulo sob peye jabo.

tar jonno amra 

1. `spatie/laravel-permission` install korbo.
    install korle amra structure pabo, but data amader dite hobe, sei jonno amra seeder use kore table gulo te data seed korbo
2. Seeder ready korechi - permission, role, permission assign to role, and role assign to users.

# Others

resource.action = কোন resource-এর উপর কী কাজ করার permission

guard_name = web মানে হলো:
এই permission কোন authentication guard-এর জন্য প্রযোজ্য।

Laravel-এর authentication system-এ একাধিক guard থাকতে পারে।

'guards' => [
'web' => [
'driver' => 'session',
'provider' => 'users',
],

    'api' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],
],


web
api

web সাধারণত browser/session-based authentication-এর জন্য ব্যবহৃত হয়।
api API authentication-এর জন্য ব্যবহৃত হতে পারে।

কেন permission-এর সাথে guard রাখতে হবে?
   ধরো তুমি একই নামের permission দুইটা guard-এর জন্য রাখতে চাও:
   users.view | web
   users.view | api

এগুলো দেখতে একই permission হলেও তারা আলাদা guard-এর context-এ থাকতে পারে।

# keno amra endpoint a permission use kori ?

Route::get('/users', [AdminController::class, 'index'])
->middleware('permission:users.view');

amar question hocche - ami to akta user ke role assign kore dicci, abar sei user er token niye akta endpoint run korchi,
tahole keno abar endpoint a middleware assign korte hoi ?

eita ki emon hocche, amra to kothaou endpoint a permission dei ni, tai dicci. ??

amar observation hocche - amra user ke role assign kori, sei user er token niye ei endpoint use korbo,
amra janlam oi user er ki ki permission ache, but amra jani na, sei user kon kon endpoint run korte parbe seta to kothaou deya nai.


হ্যাঁ — তোমার confusion-টা একদম valid। এখানে আসলে দুইটা আলাদা জিনিস কাজ করছে:

User-এর role-এর মাধ্যমে সে কী কী permission পাবে

ধরো তুমি Admin role বানালে:
Admin
├── users.view
├── users.create
├── users.update
└── users.delete

তারপর:
$user->assignRole('Admin');

এখন Laravel/Spatie জানে:
এই user-এর users.view, users.create, users.update, users.delete permission আছে।

কিন্তু একটা প্রশ্ন এখনও বাকি:
কোন endpoint call করার জন্য কোন permission দরকার?
Laravel নিজে থেকে এটা জানে না।


Role দেওয়ার পর Laravel কী জানে?
ধরো তুমি request করছো:

```
GET /admin/users
Authorization: Bearer <token>
```


`auth:sanctum প্রথমে দেখে`: এই token-এর user কে?

ধরো user হলো:
```
Rahim
    Role: Admin
Permissions:
    users.view
    users.create
    users.update
    users.delete
```

এখন Laravel জানে Rahim-এর users.view permission আছে।

কিন্তু Laravel কোথা থেকে জানবে `/admin/users` endpoint-এর জন্য users.view দরকার?

এই information কোথাও না দিলে সে জানবে না।

সেজন্য:

->middleware('permission:users.view')

দিচ্ছো।

তোমার Laravel code-এ

```php
Route::get('/users', [AdminController::class, 'index'])
->middleware('permission:users.view');
```
এখানে বলা হচ্ছে: `/admin/users` endpoint-এ ঢুকতে users.view permission লাগবে।

আর user-এর role:
Admin
↓
users.view

বলে:
এই user-এর users.view permission আছে।

তারপর permission middleware দুইটা information মিলায়:

Endpoint requirement
↓
users.view

User permission
↓
users.view

        ↓

       ✅ Allow

যদি user-এর permission না থাকে:

Endpoint requirement
↓
users.view

User permission
↓
products.view

        ↓

       ❌ Deny

তাহলে "role assign করলাম" মানে কী?

এটা খুব গুরুত্বপূর্ণ।

তুমি যখন: $user->assignRole('Admin'); করো, তুমি endpoint protection করছো না।
তুমি বলছো: এই user কোন permissions পাবে?
অন্যদিকে: ->middleware('permission:users.view')
বলছে: এই endpoint ব্যবহার করতে কোন permission লাগবে?

অর্থাৎ:
ROLE
↓
কোন permission user পাবে?


MIDDLEWARE
↓
কোন permission endpoint-এর জন্য required?

তোমার পুরো flow

তোমার request:

```
GET /admin/users
Authorization: Bearer TOKEN
```

তারপর roughly:
Request
│
▼
auth:sanctum
│
▼
Token valid?
/         \
No           Yes
│             │
401          User found
│
▼
permission:users.view
│
▼
User-এর permission আছে?
/          \
No            Yes
│              │
403              │
▼
Controller
index()

তাই তোমার route:
```php
Route::prefix('admin')->middleware('auth:sanctum')
->group(function() {
        Route::get('/users', [AdminController::class, 'index'])
            ->middleware('permission:users.view');
 });
```

এর অর্থ:
auth:sanctum
↓
তুমি কে?

permission:users.view
↓
তোমার এই কাজ করার permission আছে?

Controller
↓
কাজটা execute করো

## "Endpoint-এ permission না দিলে?"

এটাই তোমার মূল প্রশ্নের উত্তর।
যদি তুমি লেখো: Route::get('/users', [AdminController::class, 'index']);

এবং শুধু:
->middleware('auth:sanctum')

থাকে, তাহলে authentication হবে।
অর্থাৎ:

```text
যে user-এর valid token আছে, সে endpoint-এ ঢুকতে পারবে।
তার role আছে কি না, Admin কি না, users.view permission আছে কি না — এসব permission middleware না দিলে automatically check হবে না।
তাই role assign করে দিলেই endpoint secure হয়ে যায় না।
```


## একটা গুরুত্বপূর্ণ distinction

তুমি যদি বলো:

> "Admin role-এর user-ই /admin/users access করতে পারবে"

```
তাহলে role middleware ব্যবহার করতে পারো:
->middleware('role:Admin')
```

আর যদি বলো:
> "যার users.view permission আছে, সে /admin/users access করতে পারবে"
তাহলে:

```
->middleware('permission:users.view')
```

Permission-based system সাধারণত বেশি flexible।
কারণ:

```
Admin
└── users.view

Manager
└── users.view

Support
└── users.view

```

তিনটা আলাদা role হলেও সবাই /users দেখতে পারবে।

* তাই role = user কী access পায়, আর permission middleware = নির্দিষ্ট resource/action-এর দরজায় কী access লাগবে।


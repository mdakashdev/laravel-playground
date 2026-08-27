# Basic Understanding

Role based permission

amra kono user by user permission dibo na, amra permission dibo akta `role` er upor.

tar jonno amra `role based` boli.

tahole 1 ta role jei category te porbe, sei categorir sokol user ke amara sei role assign kore dite pari.

like - 1 ta `role: admin`
ei role a permission dibo - `user create, update and delete` korte parbe,

then jei koijon user ke amra ei permission gulo dite cai sei user gulo ke `admin` role ta assign kore dibo.

so ei kajta korte hole amader dorkar; 4 ta step

1. role
2. permission
3. one role many permissions
4. role assign to users


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

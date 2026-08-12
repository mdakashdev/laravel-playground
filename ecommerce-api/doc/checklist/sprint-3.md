# Sprint 3 — Role & Permission (RBAC - Role-Based Access Control)

## Tasks

- Install & Configure RBAC
  আমরা **Spatie Laravel Permission** ব্যবহার করব।
- Permission Seeder
  Application-এর সব permission database-এ seed করা।
- Role Seeder
  Default application roles তৈরি করা এবং permissions assign করা।
- Assign Role to User
  Admin user তৈরি হলে তাকে `admin` role assign করা এবং authenticated user-এর role management তৈরি করা।
- Role Middleware
  শুধু নির্দিষ্ট role-এর user যেন protected endpoint access করতে পারে।


## Sprint 3 Progress

```text
✅ Task 1 — Install & Configure RBAC
✅ Task 2 — Permission Seeder
✅ Task 3 — Role Seeder
✅ Task 4 — Assign Role to User
✅ Task 5 — Role Middleware
⬜ Task 6 — Permission Middleware
⬜ Task 7 — Admin APIs
⬜ Task 8 — Feature Tests
⬜ Task 9 — Refactor
⬜ Task 10 — Cleanup
```

# ✅ Checklist

## Task 1: Install & Configure RBAC

* [ ] Spatie Permission installed
* [ ] Configuration published
* [ ] Migration completed
* [ ] `HasRoles` added to User
* [ ] RBAC tables created
* [ ] Cache cleared
* [ ] Tinker verified
* [ ] Commit & Push

## Task 2: Permission Seeder

* [ ] `PermissionSeeder` তৈরি
* [ ] 12 permissions
* [ ] Duplicate protection
* [ ] `DatabaseSeeder` registered
* [ ] Seeder executed
* [ ] Tinker verified
* [ ] Commit & Push

## Task 3: Role Seeder

* [ ] `RoleSeeder` তৈরি
* [ ] `admin`
* [ ] `manager`
* [ ] `customer`
* [ ] Permissions assigned
* [ ] Seeder registered
* [ ] Seeder executed
* [ ] Tinker verified
* [ ] Commit & Push

## Task 4: Assign Role to User

* [ ] `AdminUserSeeder`
* [ ] Default admin user
* [ ] Password hashed
* [ ] `admin` role assigned
* [ ] Duplicate-safe
* [ ] Seeder order correct
* [ ] Role verified
* [ ] Permissions verified
* [ ] Admin login verified
* [ ] Commit & Push

## Task 5: Role Middleware

* [ ] `RoleMiddleware`
* [ ] Multiple roles supported
* [ ] Middleware alias registered
* [ ] Admin access works
* [ ] Customer blocked
* [ ] Unauthenticated blocked
* [ ] Commit & Push


# Draft

> role and permission er jonno amra spatie (স্পাটি) package use korbo

- spatie install then publish after thar migrate then HasRole trait implement 
- 5 ta table create hobe
- cache clear 
- and tinker a verfiy kora role and permision create hoyeche kina!

task2 and task3:

- permission and role seder create korlam
- 12 ta permission korechi - 3 ta modules a users, products and orders
- seeder registraion in database seeder using call method
- run seeder and permission table check in tinker

- 3 ta role create korbo - admin, manager and customer then permission gulo assign korbo using syncPermissions
- syncPermissions use korar pore pivot table eigulo insert hoi - many to many relation a
- registration, then seed then verify in tinker Role::with('permissions')->get();

task4:
- user er role ber korar jonno : $user->roles->pluck('name');
- user er all roles ber korar jonno : $user->getAllPermissions()

task5: 
- akta nidisto endpoint ke middleware er moddhe niye aslam se jonno
- middleware create korechi - then authentication check then authrizaton check korechi kono role ache kina
- middleware registration korechi
- route a assigne korechi, and route theke middleware er argument niyechi role

# Important

- firstOrCreate eita mane age theke thakle create korbe na, mane আগে থাকলে নতুন user তৈরি করবে না।

- must rbac a ki korle kon table a jai, puro senarion ta visual korte hobe, not memory just note and draw

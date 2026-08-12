# RBAC

## laravel package - spatie/laravel-permission


## Question

```text
amar prosno,  permission check korear somai oi user er kono role ache kina seta check korte hobe na ??
mane age to role dekhe then permission, because user to role er upor define kora, tahole permision kivabe hocche
```

**`hasAnyPermission()` তুমি permission check করলেও, সেটা আলাদাভাবে আবার `role` check করার জন্য তোমাকে code লিখতে হয় না।** Spatie internally user-এর role → permission relationship দেখে permission resolve করতে পারে।

তোমার database relationship:

```text
User
 │
 │ model_has_roles
 ↓
Role
 │
 │ role_has_permissions
 ↓
Permission
```

ধরো:

```text
User #5
   ↓
customer
   ↓
products.view
```

এখন তুমি:

```php
$request->user()->hasAnyPermission([
    'products.view',
]);
```

করলে Spatie internally দেখবে:

> এই User-এর `products.view` permission আছে কি?

এখানে User-এর direct permission না থাকলেও তার **assigned role-এর permission** থাকলে check pass করবে।

---

### তাহলে `RoleMiddleware` কেন আগে করেছিলাম?

এখানে দুইটা middleware-এর উদ্দেশ্য আলাদা।

#### RoleMiddleware

```php
Route::middleware('role:admin')
```

এর অর্থ:

> **এই route-এ শুধু admin role-এর user ঢুকতে পারবে।**

এখানে permission-এর দরকার নেই।

---

#### PermissionMiddleware

```php
Route::middleware('permission:products.view')
```

এর অর্থ:

> **এই route-এ যার `products.view` permission আছে সে ঢুকতে পারবে।**

সে permission role-এর মাধ্যমে এসেছে নাকি direct permission হিসেবে এসেছে—Spatie সেটা resolve করে।

---

### কিন্তু তুমি চাইলে দুটোই check করতে পারো

ধরো requirement:

> শুধু admin এবং manager-এর মধ্যে যাদের `products.update` permission আছে তারাই access পাবে।

তাহলে:

```php
Route::middleware([
    'role:admin,manager',
    'permission:products.update',
])
```

এখানে দুটো condition:

```text
User
 ↓
admin/manager role আছে?
 ↓ Yes
products.update permission আছে?
 ↓ Yes
Access
```

### সবচেয়ে গুরুত্বপূর্ণ distinction

**Role check:**

> "তুমি কে?"

```text
admin?
manager?
customer?
```

**Permission check:**

> "তুমি কী করতে পারো?"

```text
products.view?
products.update?
orders.create?
```

তাই সাধারণত application-এ **business access control-এর জন্য permission check বেশি flexible**।

উদাহরণ:

```php
Route::middleware('permission:products.update')
```

এটা বলছে না যে user অবশ্যই `manager` হতে হবে। শুধু বলছে তার `products.update` permission থাকতে হবে।

আর তোমার current setup-এ সেই permission role-এর মাধ্যমে এসেছে:

```text
User → Role → Permission
```

সুতরাং **permission middleware-এ আলাদা করে role middleware চালানো বাধ্যতামূলক না।**

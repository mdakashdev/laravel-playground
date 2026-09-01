# Feature Test

amra 3 vage vag korte pari - 

1. Environment setup
2. core setup - setUp()
3. test

## Step 
1. create & configure / setup `.env.testing` & `.env.testing`
2. database তৈরি
3. APP_KEY generate
4. Test migration
5. setup phpunit.xml 
6. Test run


```
6. UserFactory verify
7. RefreshDatabase setup
8. Test run
```

# Environment setup 

## .env.testing তৈরি

Laravel test চালানোর সময় সাধারণত `.env.testing` ব্যবহার করা সবচেয়ে নিরাপদ। `.env.testing` হবে PHPUnit/Feature Test-এর জন্য।

.env.testing ke .gitignore a dibo na, because others team member ra test korbe. / or na dite paro. depend on you or on your project.

`.env.testing` configure:

```dotenv
APP_NAME="Ecommerce API Test"
APP_ENV=testing
APP_KEY=base64:YOUR_TEST_APP_KEY
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stderr

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=ecommerce_testing
DB_USERNAME=laravel
DB_PASSWORD=secret

CACHE_STORE=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync

MAIL_MAILER=array

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
```

## Make Database

setar jonno - root diye login korte hobe 

```bash
docker compose exec db bash
```

```bash
mysql -uroot -proot
CREATE DATABASE IF NOT EXISTS ecommerce_testing;
show databases;
USE ecommerce_testing;
```

- laravel user-কে ওই database-এর permission দেওয়া : 

```sql
GRANT ALL PRIVILEGES ON ecommerce_testing.* TO 'laravel'@'%'; FLUSH PRIVILEGES;
```

check permission:

```sql
SHOW GRANTS FOR 'laravel'@'%';
```

## Test APP_KEY Generate

- তাহলে testing-এর জন্য আলাদা key কেন?
    কারণ testing environment আর production environment আলাদা রাখা ভালো।

ধরো তোমার main .env:
```dotenv
APP_ENV=local
APP_KEY=base64:MAIN_KEY
```

আর .env.testing:
```dotenv
APP_ENV=testing
APP_KEY=base64:TEST_KEY
```
এটা ভালো practice।



carefully generate korbe, because jeno main applicaiton er `APP_KEY` change na hoye jai.

sei jonno - always main application er `APP_KEY` akta backup rakhbe, env file ei duplicate kore, comment kore rakha jai.


```bash
php artisan about --env=testing
php artisan key:generate --env=testing
```

## migrate 

```bash
php artisan migrate --env=testing
php artisan migrate:status --env=testing
```

## phpunit.xml

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

এর বদলে:

```xml
<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_DATABASE" value="ecommerce_testing"/>
```

## Test run

এরপর :

```bash
docker compose exec app php artisan test
```

যদি test database নিয়ে কোনো error না আসে, তাহলে setup ঠিক আছে।


---

# core setup - setUp()

## RefreshDatabase

Feature test class-এ:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;
```

তারপর:

```php
class UserManagementTest extends TestCase
{
    use RefreshDatabase;
}
```

এটার ফলে প্রতিটি test-এর database state clean থাকবে।

---

## Seeder Strategy

RBAC test-এর জন্য আমাদের:

```text
PermissionSeeder
RoleSeeder
```

দরকার।

Test-এর ভিতরে:

```php
$this->seed([
    PermissionSeeder::class,
    RoleSeeder::class,
]);
```

তারপর test user তৈরি করবে।

উদাহরণ:

```php
$admin = User::factory()->create();
$admin->assignRole('admin');
$token = $admin->createToken('test-token')->plainTextToken;
```

---

## Factory Check

দেখো:

```text
database/factories/UserFactory.php
```

এটা properly কাজ করছে কিনা:

```bash
docker compose exec app php artisan tinker --env=testing
```

তারপর:

```php
User::factory()->create();
```

যদি User create হয় → factory ঠিক আছে।

---

## Test Environment Verify

একটা temporary test run করো:

```bash
docker compose exec app php artisan test --env=testing
```

অথবা:

```bash
docker compose exec app php artisan test
```

Laravel PHPUnit run করার সময় testing environment automatically ব্যবহার করবে।

---

## Admin Test User

প্রতিটি test-এর জন্য production `admin@example.com` ব্যবহার করার দরকার নেই।

Test user তৈরি করবে:

```php
$admin = User::factory()->create();
$admin->assignRole('admin');
```

এটা বেশি clean।

---

## Customer Test User

```php
$customer = User::factory()->create();
$customer->assignRole('customer');
```

তারপর:

```php
$token = $customer->createToken('test-token')->plainTextToken;
```

---

## Step 11 — Sanctum Test

API request:

```php
$response = $this
    ->withToken($token)
    ->getJson('/api/v1/admin/users');
```

তারপর:

```php
$response->assertStatus(200);
```

Customer:

```php
$response = $this
    ->withToken($token)
    ->getJson('/api/v1/admin/users');

$response->assertStatus(403);
```

---



## Step 14 — Very Important Safety Check ⚠️

Test চালানোর আগে নিশ্চিত হও:

```text
APP_ENV=testing
DB_DATABASE=ecommerce_test
```

কারণ:

```php
RefreshDatabase
```

database-এর table/state reset করতে পারে।

**কখনোই production database-এর ওপর test চালানো যাবে না।**

তোমার local development DB:

```text
ecommerce
```

Test DB:

```text
ecommerce_test
```

দুটো আলাদা থাকবে।

---

## 🎯 আমাদের এই Project-এর Testing Rules

এগুলো এখন থেকে follow করব:

```text
Feature Test
    ↓
.env.testing
    ↓
ecommerce_test
    ↓
RefreshDatabase
    ↓
Factory
    ↓
Seeder
    ↓
API Request
    ↓
Response Assert
    ↓
Database Assert
```

আর **Mail/Queue test-এর জন্য actual Mailpit ব্যবহার করব না**। Feature test-এ:

```dotenv
QUEUE_CONNECTION=sync
MAIL_MAILER=array
```

রাখব। এতে test দ্রুত এবং deterministic হবে।

---

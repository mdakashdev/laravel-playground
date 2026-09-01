# Feature Test

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

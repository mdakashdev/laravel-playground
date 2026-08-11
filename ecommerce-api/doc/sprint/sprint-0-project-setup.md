# Sprint 0 — Project Setup

## Task 1 — Create Laravel Project

### Step 1

নিশ্চিত করো তোমার environment:

```bash
php -v
composer --version
git --version
docker --version
docker compose version
```

---

### Step 2

Project create করো।

```bash
composer create-project laravel/laravel ecommerce-api
```

specific version
```bash
composer create-project laravel/laravel="^12.0" ecommerce-api
```
---

### Step 3

Project folder-এ যাও।

```bash
cd ecommerce-api
```

---

### Step 4

Laravel version check করো।

```bash
php artisan --version
```

Expected:

```text
Laravel Framework 12.x.x
```

---

### Step 5

Project run করে verify করো।

```bash
php artisan serve
```

Browser:

```
http://127.0.0.1:8000
```

Laravel welcome page দেখা যাবে।

---

Laravel **12.64.0** ✔️

---

# Task 2: Git Setup

### Step 1

Git initialize হয়েছে কিনা দেখো।

```bash
git status
```

Expected:

```text
On branch main
```

---

### Step 2

`.gitignore` check করো।

```bash
cat .gitignore
```

নিশ্চিত করো এগুলো আছে:

```text
/vendor
/node_modules
.env
/public/build
/storage/*.key
```

---

# Task 3: Docker Setup

in detail - @doc/docker-setup.md

# Task 4 — Configure `.env`

## Step 1

`.env`-এ নিচের values update করো।

## Step 2

Docker start করো।

```bash
docker compose up -d --build
```

---

## Step 3

সব container running কিনা দেখো।

```bash
docker compose ps
```

Expected: সব service `Up` দেখাবে।

---

# Task 5: Verify Docker & Laravel

## Step 1

সব container running আছে কিনা দেখো।

```bash
docker compose ps
```

---

## Step 2

Laravel container-এ ঢুকো।

```bash
docker compose exec app bash
```

Expected prompt:

```bash
root@xxxxxxxx:/var/www/html#
```

---

## Step 3

Container-এর ভিতরে Laravel version check করো।

```bash
php artisan --version
```

Expected:

```text
Laravel Framework 12.64.0
```

---

## Step 4

PHP extensions verify করো।

```bash
php -m
```

নিশ্চিত করো এগুলো আছে:

* pdo_mysql
* mbstring
* redis (এটা এখন না থাকলেও সমস্যা নেই, পরে install করব)
* gd
* zip
* exif
* pcntl

---

## Step 5

Container থেকে বের হয়ে আসো।

```bash
exit
```

---

✅ **Check Passed**

---

# Task 6: Database Verify & Migration

## Step 1

Laravel container-এ ঢুকো।

```bash
docker compose exec app bash
```

---

## Step 2

Database connection verify করো।

```bash
php artisan db:show
```

Expected:

* Database: `ecommerce`
* কোনো error থাকবে না।

---

## Step 3

Migration run করো।

```bash
php artisan migrate
```

Expected:

```text
INFO  Running migrations.

... DONE
```

---

## Step 4

Migration status check করো।

```bash
php artisan migrate:status
```

সব migration `Ran` দেখাবে।

---

## Step 5

Container থেকে বের হয়ে আসো।

```bash
exit
```
---

# Task 7: GitHub Repository
- nothing, no need to note

# Task 8: Readme
- nothing, no need to note

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


## ✅ Checklist

* [ ] PHP install আছে
* [ ] Composer install আছে
* [ ] Git install আছে
* [ ] Docker install আছে
* [ ] Laravel 12 project create হয়েছে
* [ ] `php artisan --version` কাজ করছে
* [ ] Welcome page open হচ্ছে

---


### ✅ Checklist

* [ ] Git repository initialized
* [ ] Branch name `main`
* [ ] `.gitignore` exists
* [ ] `.env` ignored
* [ ] `vendor` ignored


---

✅ **Check Passed**

Laravel **12.64.0** ✔️

---

# Sprint 0 — Task 2: Git Setup

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



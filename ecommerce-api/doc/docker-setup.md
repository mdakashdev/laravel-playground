# Sprint 0 — Task 3: Docker Setup

### Goal

Project চালু হবে এই services দিয়ে:

* PHP 8.3 (FPM)
* Nginx
* MySQL 8
* Redis
* Mailpit
test
---

## Step 1

Project root-এ এই structure তৈরি করো।

```text
docker/
├── nginx/
│   └── default.conf
└── php/
    └── Dockerfile

docker-compose.yml
```

---

## Step 2

এই image versions ব্যবহার করবে।

| Service | Version |
| ------- | ------- |
| PHP     | 8.3-fpm |
| Nginx   | latest  |
| MySQL   | 8.4     |
| Redis   | 7       |
| Mailpit | latest  |

---

## Step 3

Container names

```text
ecommerce-app
ecommerce-nginx
ecommerce-db
ecommerce-redis
ecommerce-mailpit
```

---

## Step 4

Volumes

* Project source mount করবে।
* MySQL data persistent হবে।

---

## Step 5

Network

একটি custom bridge network ব্যবহার করবে।

নাম:

```text
ecommerce-network
```

---

### ✅ Checklist

* [ ] `docker-compose.yml` তৈরি হয়েছে
* [ ] `docker/php/Dockerfile` তৈরি হয়েছে
* [ ] `docker/nginx/default.conf` তৈরি হয়েছে
* [ ] ৫টি service define হয়েছে
* [ ] custom network আছে
* [ ] MySQL volume আছে

---

শেষ হলে শুধু লিখবে:

```text
Done
```

আমি structure review করব, তারপর Docker files-এর content step-by-step করব।

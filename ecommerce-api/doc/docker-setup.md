# Sprint 0 — Task 3: Docker Setup

### Goal

Project চালু হবে এই services দিয়ে:

* PHP 8.3 (FPM)
* Nginx
* MySQL 8
* Redis
* Mailpit

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

Network - একটি custom bridge network ব্যবহার করবে।

নাম:

```text
ecommerce-network
```

Summary: 

- Dockerfile: ami jani na, kivabe dockerfile likhte hoi, keno likhchi but AI er kache theke niye likhe nilam
- nginx: smae, nginx likhe nilam, just akta jinish jani - index.php te forward korteche
- docker-compose.yml a : 5 ti service crete korlam and 1ta volumen and 1 ta network

* [ ] File path: `docker/nginx/default.conf`
* [ ] `root` = `/var/www/html/public`
* [ ] `fastcgi_pass` = `ecommerce-app:9000`

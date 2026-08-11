# Question
> 1. amar docker desktop a age theke 4/5 docker ache, seigulo te ki kono conflit hobe?

> 2. ei migrate ta kothai holo? container er vitore , mane amar pc te local a phpmyadmin a kono db thaklo na

> 3. mysql-data name akta volume storage name banalam as like db name, aar projuect mount korar moto, local code er sathe container er code jeivabe mount / sync kore, sei kaj ta service: db: te je volume path dekhae seta boli je, container er moddhe ei path mysql data dau form volume mysql-data theke

> 4. Laravel 12-এ exception handling কোথায় configure হয় সেটা identify করো ?

> 5. tumi ki globaly api exception handling korte paro?

> 6. akta migraton create koro, info uuid [uuid (unique)], phone [string, nullable, unique], avatar [string, nullable], status [boolean, default true]

> 7. validation rules likho nicher info diye 

> 8. Dockerfile create korar jonno akta question kora hoyeche - @doc/make-docker-compose-yml.md

```text
name ( required, string, min:3, max:100 )
email (required, email, max:255, unique:users,email)
password (required, confirmed, min:8, max:255)
phone   (nullable, string, unique:users,phone)
avatar  (prohibited)
```


## 1. amar docker desktop a age theke 4/5 docker ache, seigulo te ki kono conflit hobe?

না, **কোনো conflict হবে না**, যদি নিচের শর্তগুলো মানা থাকে।

### ১. Container name

আমরা unique নাম ব্যবহার করছি:

```text
ecommerce-app
ecommerce-nginx
ecommerce-db
ecommerce-redis
ecommerce-mailpit
```

### ২. Network

আমাদের নিজস্ব network:

```text
ecommerce-network
```

### ৩. Volume

আমাদের নিজস্ব volume:

```text
mysql-data
```

---

### ⚠️ শুধু Port conflict হতে পারে

যদি আগের কোনো container এই port ব্যবহার করে:

```text
8000
3306
6379
8025
1025
```

তাহলে `docker compose up` error দেবে।

চেক করো:

```bash
docker ps
```

যদি এই port-গুলো আগে থেকেই use হয়, তাহলে আমাদের compose file-এ শুধু **host port** change করব। যেমন:

```yaml
8001:80
3307:3306
6380:6379
8026:8025
1026:1025
```

**Container-এর ভিতরের port কখনো change করব না।**

---

👉 **Task:** আগে এই command চালাও:

```bash
docker ps or docker ps -a
```

✅ কোনো সমস্যা নেই।

তোমার সব container **Exited** অবস্থায় আছে, তাই এগুলো এখন port ব্যবহার করছে না।

আমরা **বর্তমান ports-ই** ব্যবহার করব:

```text
8000 -> Nginx
3306 -> MySQL
6379 -> Redis
8025 -> Mailpit UI
1025 -> SMTP
```

**কোনো port change করার দরকার নেই।**

## 2. ei migrate ta kothai holo? container er vitore , mane amar pc te local a phpmyadmin a kono db thaklo na

হ্যাঁ, **container-এর MySQL-এর ভিতরে হয়েছে**।

এটাই production-standard workflow। Host machine-এর MySQL আর Docker-এর MySQL আলাদা।

তুমি `.env`-এ দিয়েছো:

```dotenv
DB_HOST=db
```

এখানে `db` হলো Docker-এর MySQL service, তোমার PC-এর localhost MySQL না।

তাই local phpMyAdmin-এ database দেখাবে না।

---

আমরা দুইটা standard option-এর একটাতে যাব:

### Option 1 (Recommended) ✅

Docker-এর সাথে **phpMyAdmin** service add করব।

তাহলে browser-এ:

```text
http://localhost:8080
```

থেকে Docker MySQL দেখতে পারবে।

**Production-style development-এ এটা খুব common।**

---

### Option 2

Terminal দিয়ে MySQL-এ ঢুকবে:

```bash
docker compose exec db mysql -u laravel -p
```

---

**আমার recommendation:** আমরা **Option 1** ব্যবহার করব। এতে database inspect করা সহজ হবে এবং development-এর জন্য standard setup হবে।

## Question 3. mysql-data name akta volume storage name banalam as like db name, aar projuect mount korar moto, local code er sathe container er code jeivabe mount / sync kore, sei kaj ta service: db: te je volume path dekhae seta boli je, container er moddhe ei path mysql data dau form volume mysql-data theke 

হ্যাঁ, আপনার ধারণাটা মোটামুটি ঠিক। শুধু একটু পরিষ্কারভাবে বলি।

```yaml
services:
  db:
    volumes:
      - mysql-data:/var/lib/mysql

volumes:
  mysql-data:
```

এর অর্থ হলো:

1. **Root level**

   ```yaml
   volumes:
     mysql-data:
   ```

   👉 `mysql-data` নামে একটি Docker volume (স্টোরেজ) তৈরি করো।

2. **Service level**

   ```yaml
   volumes:
     - mysql-data:/var/lib/mysql
   ```

   👉 `mysql-data` volume-টা **container-এর `/var/lib/mysql`** ফোল্ডারে mount করো।

MySQL স্বাভাবিকভাবেই `/var/lib/mysql`-এ তার database file রাখে। তাই MySQL যখন ডেটা লিখবে, সেটা আসলে `mysql-data` volume-এ সংরক্ষিত হবে।

---

### Project mount-এর সাথে তুলনা

**Project source mount:**

```yaml
volumes:
  - .:/app
```

* `.` = আপনার local project folder
* `/app` = container-এর folder

অর্থাৎ, **Local folder ↔ Container folder** sync হচ্ছে।

---

**MySQL volume:**

```yaml
volumes:
  - mysql-data:/var/lib/mysql
```

* `mysql-data` = Docker-এর managed storage
* `/var/lib/mysql` = container-এর folder

অর্থাৎ, **Docker Volume ↔ Container folder** connect হচ্ছে।

---

### পার্থক্যটা এক লাইনে

* `.:/app` → **Local folder ↔ Container**
* `mysql-data:/var/lib/mysql` → **Docker Volume ↔ Container**

তাই আপনার এই কথাটা—

> "container এর মধ্যে এই path-এ mysql-data volume-এর data দাও"

—এটা মূল ধারণা হিসেবে ঠিক। আরও নির্ভুলভাবে বললে হবে:

> **`mysql-data` volume-কে container-এর `/var/lib/mysql` path-এ mount করো, যাতে MySQL-এর সব data ওই volume-এ সংরক্ষিত হয়।**


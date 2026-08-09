# Command

1. working directory / container in hobar jonno from terminal 

```bash
docker compose exec app bash
```

2. Docker build (Cache ব্যবহার করে দ্রুত build)

```bash
docker compose build
```

3. rebuild;  --no-cache ব্যবহার করা হয় যাতে Docker পুরোনো build cache ব্যবহার না করে একদম শুরু থেকে image build করে। (সব layer নতুন করে build)

```bash
docker compose build --no-cache
```

4. Docker start

```bash
docker compose up -d
```

5. Docker remove korar jonno 

```bash
docker compose down
```

6. Docker diye db te entry

```bash
docker compose exec db bash
```

```bash
docker compose exec db mysql -u laravel -p ecommerce
```

then password provide korea
SHOW TABLES;
DESCRIBE users;
SHOW COLUMNS FROM users;

# command - redis

1. redis a in hobar jonno

```bash
docker compose exec redis bash
```
then: 
```text
redis-cli
```

2. db and others all details dekhar jonno 

```bash
info keyspace
```

output like - 

## Keyspace
db1:keys=1,expires=1,avg_ttl=539665,subexpiry=0

## db select
Here, `db1` dekha jacche aar seta select korar jonno
- select 1 

after select 1 then 127.0.0.1:6379[1]> eita dekha jabe

## keys

> all key dekhar jonno - `keys *` dile sob key gulo dekha jabe

## get value

> get api-dev-database-api-dev-cache-my_test_key

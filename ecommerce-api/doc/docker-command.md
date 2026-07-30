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

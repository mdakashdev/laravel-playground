এই task-এর মূল কথা হলো **Laravel-এর default email content পরিবর্তন না করে শুধু Notification-কে queued করা**।

---

## Step 1

`.env`

```dotenv
QUEUE_CONNECTION=database
```

---

## Step 2

Queue table না থাকলে

```bash
docker compose exec app php artisan queue:table
docker compose exec app php artisan migrate
```

---

## Step 3

Worker চালাও

```bash
docker compose exec app php artisan queue:work
```

---

# Step 4 — Verification Email Queue

Laravel-এর `VerifyEmail` notification extend করো।

```bash
docker compose exec app php artisan make:notification QueuedVerifyEmail
```

```php
<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueuedVerifyEmail extends VerifyEmail implements ShouldQueue
{
    //
}
```

কোনো method override করার দরকার নেই।

---

### User Model

```php
use App\Notifications\QueuedVerifyEmail;
```

```php
public function sendEmailVerificationNotification(): void
{
    $this->notify(new QueuedVerifyEmail());
}
```

এখন Register করলে Verification email queue-তে যাবে।

---

# Step 5 — Reset Password Queue

Notification তৈরি করো।

```bash
docker compose exec app php artisan make:notification QueuedResetPassword
```

```php
<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueuedResetPassword extends ResetPassword implements ShouldQueue
{
    public function __construct(string $token)
    {
        parent::__construct($token);
    }
}
```

---

### User Model

Laravel password reset notification override করবে।

```php
use App\Notifications\QueuedResetPassword;
```

```php
public function sendPasswordResetNotification($token): void
{
    $this->notify(new QueuedResetPassword($token));
}
```

এতেই Forgot Password email-ও queue হবে।

---

# কেন শুধু `ShouldQueue`?

Laravel-এর Notification system-এ কোনো Notification যদি `ShouldQueue` implement করে, তাহলে `notify()` কল করার সময় সেটি **সরাসরি send না হয়ে queue job হিসেবে** `jobs` table-এ যোগ হয়। Queue worker (`php artisan queue:work`) পরে সেই job process করে email পাঠায়।

তাই:

* Email template পরিবর্তন করতে হয় না।
* Subject পরিবর্তন করতে হয় না।
* Mail content পরিবর্তন করতে হয় না।
* Verification URL বা Reset URL তৈরির logic-ও একই থাকে।

শুধু `ShouldQueue` implement করলেই default behavior queued হয়ে যায়।

---

# Testing

### Register

```text
POST /register
        ↓
201 Created
        ↓
jobs table
        ↓
queue:work
        ↓
Mailpit
```

---

### Forgot Password

```text
POST /forgot-password
        ↓
200 OK
        ↓
jobs table
        ↓
queue:work
        ↓
Mailpit
```

---

### Worker বন্ধ

Worker বন্ধ রেখে Register করলে:

* API সঙ্গে সঙ্গে response দেবে।
* `jobs` table-এ pending job থাকবে।
* Email তখনও Mailpit-এ যাবে না।

---

### Worker চালু

```bash
docker compose exec app php artisan queue:work
```

Worker চালু করলেই pending job process হবে এবং Mailpit-এ email পৌঁছে যাবে।

এটাই Laravel-এর recommended ও standard implementation, এবং তোমার task-এর "existing email content পরিবর্তন করবে না" শর্তও পুরোপুরি মেনে চলে।

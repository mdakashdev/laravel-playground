Laravel-এ **Password Broker** হলো authentication system-এর একটি component, যা **password reset process** পরিচালনা করে। এটি reset token তৈরি করা, token যাচাই করা, এবং সফলভাবে password update করার কাজ সহজ করে।

সহজভাবে বললে, Password Broker হলো **password reset-এর "manager"**।

### এটি কী কী করে?

* Password reset token তৈরি করে
* Token database-এ সংরক্ষণ করে
* User-এর email-এ reset link পাঠানোর জন্য token দেয়
* Reset token valid কিনা যাচাই করে
* Password update করে
* Token ব্যবহার হয়ে গেলে সেটি invalidate করে

### Flow

```
User clicks "Forgot Password"
          │
          ▼
Password Broker creates a token
          │
          ▼
Email contains reset link
          │
          ▼
User opens link and submits new password
          │
          ▼
Password Broker verifies token
          │
          ▼
Password is updated
```

### Example

Password reset link পাঠানো:

```php
use Illuminate\Support\Facades\Password;

$status = Password::sendResetLink([
    'email' => $request->email,
]);
```

Password reset করা:

```php
$status = Password::reset(
    [
        'email' => $request->email,
        'password' => $request->password,
        'password_confirmation' => $request->password_confirmation,
        'token' => $request->token,
    ],
    function ($user, $password) {
        $user->forceFill([
            'password' => bcrypt($password),
        ])->save();
    }
);
```

### Password Broker কোথায় configure করা হয়?

`config/auth.php` ফাইলে:

```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
    ],
],
```

এখানে:

* `provider` → কোন user provider ব্যবহার হবে
* `table` → reset token কোন table-এ থাকবে
* `expire` → token কত মিনিট valid থাকবে
* `throttle` → কতক্ষণ পর আবার reset link request করা যাবে

### Multiple Password Brokers

যদি `users` এবং `admins` আলাদা model থাকে, তাহলে আলাদা broker ব্যবহার করা যায়:

```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
    ],

    'admins' => [
        'provider' => 'admins',
        'table' => 'admin_password_reset_tokens',
    ],
];
```

ব্যবহার:

```php
Password::broker('admins')->sendResetLink([
    'email' => $request->email,
]);
```

### সংক্ষেপে

**Password Broker** হলো Laravel-এর password reset service, যা token তৈরি, token verification, reset link পাঠানো এবং password update করার পুরো workflow পরিচালনা করে। এর ফলে আপনাকে token generation বা validation-এর মতো কাজগুলো নিজে implement করতে হয় না।


### **প্রশ্ন: Docker Compose ব্যবহার করে একটি Laravel Development Environment তৈরি করুন।**

একটি `docker-compose.yml` ফাইল তৈরি করুন যেখানে মোট **৫টি service** থাকবে এবং প্রতিটি service-এর জন্য নির্ধারিত version ব্যবহার করুন।:

* PHP 8.3 (FPM)
* Nginx
* MySQL 8.4
* Redis 7
* Mailpit (latest)

নিচের শর্তগুলো অবশ্যই অনুসরণ করতে হবে:

1. সব service একই **bridge network**-এর অধীনে থাকবে এবং network-এর নাম হবে **`ecommerce-network`**।
2. প্রতিটি service-এর নামে **`ecommerce-`** prefix ব্যবহার করতে হবে। যেমন: ecommerce-app, ecommerce-nginx
3. PHP service-এ project source **mount** করতে হবে, যাতে local project-এর পরিবর্তন container-এ প্রতিফলিত হয়।
4. MySQL-এর data **persistent** রাখতে হবে।
5. প্রতিটি service-এর **host port** এবং **container port** নির্ধারণ করতে হবে।
6. MySQL container চালুর সময় নিচের database configuration দিতে হবে:

    * db: `ecommerce`
    * Root Password: `root`
    * user & pass: `laravel` , `secret`
    *
7. প্রয়োজনীয় `depends_on`, `volumes`, এবং `networks` configuration যুক্ত করতে হবে যাতে service-গুলো সঠিকভাবে একসাথে কাজ করতে পারে।

**করণীয়:** উপরোক্ত শর্ত পূরণ করে একটি সম্পূর্ণ `docker-compose.yml` ফাইল লিখুন।

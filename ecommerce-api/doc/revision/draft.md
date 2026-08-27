# Tasks
1. project setup, git setup and docker setup
2. API Response Standard and Global Exception Handling
3. Production-standard API structure / folder structure / API Version Folder structure


# Important note
- You don’t need to memorize anything to do something.
- You just need to know what you want to do, which `document` you need to follow to do it, and where to find that document.
- Then, you need to `follow the document` 
- and make your own notes so that the document becomes meaningful and easy for you to understand.

```text
কোনো কিছু করার জন্য কিছুই মুখস্থ করার দরকার নেই।
শুধু জানতে হবে, আমি কী করতে চাই, সেটা করার জন্য কোন ডকুমেন্ট ফলো করতে হবে এবং সেই ডকুমেন্টটি কোথায় পাওয়া যাবে।
এরপর ডকুমেন্টটি ফলো করতে হবে এবং সেটি যেন অর্থপূর্ণ ও সহজে বোঝা যায়, সেজন্য নিজের মতো করে ভালোভাবে নোট করে রাখতে হবে।

```

# sprint - 0 : Project, Git & Docker setup

## project setup, git setup and docker setup

- before project scafollding check must php, composer, docker, docker composer and git version, if not then install
- laravel 12 project scaffolding korbo follow @sprint/sprint-0-project-setup.md (task-1)
- git setup korbo sei jonno - task-2
- docker setup for task-3
- Configure `.env` for task - 4
- Verify Docker & Laravel - task - 5
- Database Verify & Migration task -6

## For Docker

- docker and nginx file er jonno akta folder korlam 
- sekhan theke app build korlam docker-compose file a
- env set korlam then docker compose build --no-cache dilam 
- docker compose up -d 
- then done

```text
Dockerfile and default.conf dekhe copy kore nibo. then `question` theke `docker-compose.yml` nije banbo
and obossoi sei service gulo onujai `.env setup` korbo
```

> note: vule jeno na jai, local a kono db create korchi na, sob docker container a hobe


# sprint - 1 : 

## API Response Standard and Global Exception Handling

- API Response Standard: Project-এর **সব API একই JSON format** return করবে।
  - se jonno - app/Support/ApiResponse.php
- Global Exception Handling: Project-এর **সব exception** এক জায়গা থেকে handle হবে। 
  - se jono - app/Exceptions/ApiExceptionRender.php


## Production-standard API structure / folder structure / API Version Folder structure

- 8 ta bisoy niye kaj hobe
- response and exception = 2
- routes, controller, requests, service, actions and responses = 6
- ekhane Requests and Resources, and controller hocche http
- Controller আর Action-এর মাঝে orchestration/ অর্কেস্ট্রেশন layer Services তৈরি করা। 


Folder Structure
```text
app/
├── Support --> all response handle
├── Exceptions --> all exception handle
├── Actions/Auth/
├── Http/Controllers/Api/V1
├── Http/Requests/Api/V1/Auth/
├── Http/Resources/Api/V1/
├── Services/Auth/
routes/api_v1.php
```


Example: With File
```text
app/
├── Support/ApiResponse
├── Exceptions/ApiExceptionRender
├── Actions/Auth/RegisterUserAction
├── Http/Controllers/Api/V1/AuthController
├── Http/Requests/Api/V1/Auth/RegisterRequest
├── Http/Resources/Api/V1/UserResource
├── Services/Auth/AuthService
routes/api_v1.php
```

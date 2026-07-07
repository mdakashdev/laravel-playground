# capstone-project
Authentication API

- First, Think pattern - service repository pattern / service layer architecture
- Second  - need clean architecture 

Pattern
Controller -> Services -> Repositories -> Model -> Database

Full Flow
Form Request (or Postman) -> Validation (Request File)
    -> Controller -> Services -> Transaction -> 
    -> Repositories -> Model -> Database -> Resource -> Response

```text
6 endpoint
register, login
middleware need - profile, me, change-password, logout
```

Flow
```text
api -> added api.php in bootstrap (app.php) -> 
-> controller -> request -> services -> repositories -> resources
```

## Clean Architecture
```text
app/
   |- Http/
        |-Controller
        |-Requests
        |-Resources
   |- Services
        - AuthService.php
   |- Repositories  
        - UserRepository.php
   |- Policies
   |- Exceptions
```

## Validation 

## Transaction 

## Authorization 



## Controller
No Business logic.
http request rcv, service call, response return

## Services [Business logic]
Here, business rules / logic, 
transaction, external api, multiple repositories coordinate
ekhane, repositories call hote o pare abar na hote o pare.

## Repositories [Database]
only database access, no business logic.

## API Resources
api response format kora.
sensitive field hide kora.


Note: this is not clean architecture, need interface. 

Constructor Dependency Injection

# basic

* Laravel-এর auth:sanctum middleware, মূলত request-এর user authenticated কি না সেটা verify করে
*
* user - admin@example.com
* sei jokhon /users browse korbe, middleware check kobe , ei user er users.view permission ache kina.
*
* kivabe ??
*
* amader `permission` name a akta middlware register kora ache, so brower korar sathe sathe, sei middlware a cole jabe.
* sathe ami - route theke value (users.view) pass korechi ja middleware a pabo
*
* 1st, user unauthenticated hole error throw kore dibo.
* 2nd, permission check korbo, jei value ta nilam sei value ta oi user er er ache kina seta check korbo, jodi na thake error show kore dibo


# Question 

- admin@example.com er to akta role assign ache, aar role assign thakle to, sei role a permission deya ache, tahole keno ei endpoint theke users.view pass kortechi.
*
* admin, permision sob kichu to database relation kore rakha ache, but seta to kothaou check kori ni. aar checking ta middleware diye kora hai.
* so jei user diye browse kora hocche - sei user users.view permission ta achekine seta check korchi. i am understood
*
* Role assign করা আছে মানেই middleware-এর $permissions-এর সাথে role সরাসরি compare হচ্ছে না
* এটা basically বলছে: এই request করা user-এর কাছে users.view permission আছে কি?
  

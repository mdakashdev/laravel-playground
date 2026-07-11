# Observation

## নিজের ভাষায় লিখো— কোনটা Better?
Version B, ekhane IoC (Inversion of Control) use kora hoyeche, ja code ke aro testable, maintainable, ebong flexible kore tole. 
Constructor injection er maddhome `MailService` ke inject kora hoyeche, ja amader ke easily onno implementation swap korte ba testing er somoy mock korte sahajjo kore.

jodi amra Version A use kori, tahole `UserController` tightly coupled hoye jabe `MailService` er sathe, ja code ke test kora, maintain kora, ebong future changes er somoy problem create korte pare.
aro problem hote pare jodi `MailService` er kono dependency change hoy, tahole `UserController` keo modify korte hobe.


## কেন?
version B ভালো কারণ এটি dependency injection ব্যবহার করে, যা কোডটিকে আরও টেস্টযোগ্য, রক্ষণাবেক্ষণযোগ্য এবং নমনীয় করে তোলে। 

## What did I learn?

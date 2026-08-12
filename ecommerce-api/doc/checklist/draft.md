> Role and Permission er jonno amra spatie (স্পাটি) package use korbo

Role & Permission
 - Spatie
   model a trait HasRole use korte hobe
Table 
1. roles 
   amra 3 ta role create korbo (admin, manager and customer) then permission assign korbo
   after permission assign pivot table a data insert hobe role and permission id niye, table- role_has_permission
2. permissions 
    amra 3 ta module/resource er jonno 12 ta permission create korbo, field - name
3. role_has_permissions
   ekhane role and permission er id niye data insert hoi as pivot table
4. model_has_roles
5. model_has_permissions
   user ke role assign korar por - role er sathe user akta relation niye data porbe, table - model_has_roles

akta `admin user` create korlam seeder er maddhome, mane name - admin beacause jeno bujte pari sei admin
then ei user ke role assign korbo - amader role ache 3 ta (admin, manager, and customer), ei user ke role assign korbo admin
after assign - role er sathe user akta relation niye data porbe, table - model_has_roles




ির্দিষ্ট role-এর user যেন protected endpoint access করতে পারে। ???

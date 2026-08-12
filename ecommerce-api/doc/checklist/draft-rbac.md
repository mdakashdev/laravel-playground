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


# Example - 

- Permission create kora
- Role create kora
- Role er upor permission deya
- User ke role assign kora
- middleware diye check

## Step-1

select * from roles;

```mysql
+----+----------+------------+---------------------+---------------------+
| id | name     | guard_name | created_at          | updated_at          |
+----+----------+------------+---------------------+---------------------+
|  1 | admin    | web        | 2026-08-11 17:09:58 | 2026-08-11 17:09:58 |
|  2 | manager  | web        | 2026-08-11 17:09:58 | 2026-08-11 17:09:58 |
|  3 | customer | web        | 2026-08-11 17:09:58 | 2026-08-11 17:09:58 |
+----+----------+------------+---------------------+---------------------+
```

## Step-2
select * from permissions;

```mysql
----------------+------------+---------------------+---------------------+
| id | name            | guard_name | created_at          | updated_at          |
+----+-----------------+------------+---------------------+---------------------+
|  1 | users.view      | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  2 | users.create    | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  3 | users.update    | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  4 | users.delete    | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  5 | products.view   | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  6 | products.create | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  7 | products.update | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  8 | products.delete | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
|  9 | orders.view     | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
| 10 | orders.create   | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
| 11 | orders.update   | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
| 12 | orders.delete   | web        | 2026-08-11 16:58:24 | 2026-08-11 16:58:24 |
+----+-----------------+------------+---------------------+---------------------+
```

> just name entry korlam role and permission then

> then admin, manager and customer ke permission asssign korbo

admin all, 
manager ('users.view,update', 'products.view,create and update', 'orders.view,update') and 
customer ('products.view','orders.view,create')

## Step-3

we got relation : select * from role_has_permissions;

```mysql
+---------------+---------+
| permission_id | role_id |
+---------------+---------+
|             1 |       1 |
|             2 |       1 |
|             3 |       1 |
|             4 |       1 |
|             5 |       1 |
|             6 |       1 |
|             7 |       1 |
|             8 |       1 |
|             9 |       1 |
|            10 |       1 |
|            11 |       1 |
|            12 |       1 |
|             1 |       2 |
|             3 |       2 |
|             5 |       2 |
|             6 |       2 |
|             7 |       2 |
|             9 |       2 |
|            11 |       2 |
|             5 |       3 |
|             9 |       3 |
|            10 |       3 |
+---------------+---------+
```

## Step-4

now, 3 ta user create korbo and 3jon user ke 3 type er role assign korbo jeno, permisison gulo check korte pari.

### Example 

1. user-name: admin 

eita role na, name admin diyechi jeno ei user er name dekhe bujte pari take admin role deya ache.

- now, user create korar por `role` assign korbo.
- role assign hobar por seta relation entry hoi 

select * from model_has_roles;

```mysql
+---------+-----------------+----------+
| role_id | model_type      | model_id |
+---------+-----------------+----------+
|       1 | App\Models\User |       18 |
+---------+-----------------+----------+
```

ekhane dekha jai je user 18 er role 1, mane `admin@example.com` user er role hocche `admin`


## Step-5

> role middleware and permission middleware 

- 2 tar kaj hocche, user jei endpoint use korteche seita sei user er role a assign ache kina.
- kono endpoint a middlware assign kore dei - like: role:admin 
   tahole sei endpoint ekmatro jei user er admin role ache sei acces pabe.
- ei kaj ta sohoj kore diyeche spatie - $request->user()->hasAnyRole($roles)
   so, internally uporer table er relation theke seta check kore dei

- ekoi vabe permission kaj kore.

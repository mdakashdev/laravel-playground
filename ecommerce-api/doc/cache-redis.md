# Concept

- `week-04/cache.md` file a details ache

kono kichu cache korte caile, caching ta koikvabe kora jai - file, database, redis and memecached

so, amara akta endpoint theke like category cache kore rakte cai , jodi database use kori tobe, laravel er cache table a store hoi,
and sekhan theke dekhai. obossoi env te CACHE_STORE=database dite hobe

jodi amra same jinista redis a rakte cai, tobe env te CACHE_STORE=redis set kore, redis er confirue dite hobe, ekhane amra docker redis er jonno dockae 
service use kortechi.

redis database and key er prefix dekhar jonno - config/database gele dekha jabe, jodi REDIS_CACHE_DB = 1 thake tobe tobe db 1 a save hoi
seta dekhar jonno - docker compose exec redis bash then redis-cli 
then `info keyspace` dile sob details dekha jai - 
`# Keyspace
db1:keys=1,expires=1,avg_ttl=539665,subexpiry=0
`

ekhane db1 dekha jacche aar seta select korar jonno  - select 1 then 127.0.0.1:6379[1]> eita dekha jabe
then `keys *` dile sob key gulo dekha jabe
ei command dile valu pauwa jai `get api-dev-database-api-dev-cache-my_test_key`


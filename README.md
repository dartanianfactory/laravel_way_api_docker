 ##run

 ```
npm install
npm run build
docker compose build
docker compose up -d
docker exec -it lar_php bash
composer install
php artisan migrate
exit
 ```
GET http://127.0.0.1:3030/ - front

GET http://127.0.0.1:3030/api/_v1/tasks - GET ALL ( BY DESC )

GET http://127.0.0.1:3030/api/_v1/tasks?order=ASC or DESC - GET ALL ( BY created_at )

POST http://127.0.0.1:3030/api/_v1/tasks/{task} - STORE TASK

PUT http://127.0.0.1:3030/api/_v1/tasks{task} - UPDATE TASK BY ID

DELETE http://127.0.0.1:3030/api/_v1/tasks{task} - DELETE TASK BY ID
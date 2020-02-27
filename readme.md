Инструкция по установке:

1) git clone https://github.com/assel31/products.git
2) composer install
3) настроить среду: скопировать .env.example в .env создать локальную базу, прописать следующие конфигурации
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=[название базы]
DB_USERNAME=[имя пользователя]
DB_PASSWORD=[пароль]
4) php artisan key:generate
5) php artisan jwt:secret
6) php artisan migrate:refresh --seed
7) php artisan serve
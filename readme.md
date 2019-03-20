# Test Laravel application

## Requirements:
* PHP >= 7.1.3
* OpenSSL PHP Extension
* PDO PHP Extension
* mysqlnd & mysql_pdo PHP Extension
* sqlite extension for database testing
* Mbstring PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension
* Ctype PHP Extension
* JSON PHP Extension
* BCMath PHP Extension
* MySql/MariaDB database
* or docker and docker-compose to run application with docker environment

## Setup with local environment
* create config file with `cp .env.example .env`
* setup database connection at .env
* install dependencies with `composer install`
* run `php artisan key:generate`
* run `php artisan migrate` to run migrations
* run `php artisan db:seed` to seed database with test data
* `php artisan serve` to start built-in dev-server or setup your web server with php
* Transfers are handled by console command `php artisan transfers:handle`. [Setup commands scheduling](https://laravel.com/docs/5.8/scheduling#introduction)
* run `./vendor/bin/phpunit` to start test

## Setup with docker
### default containers settings:
* nginx exposes 80 port to 8080 local one
* mysql user/password root/123456
* mysql files stored at local directory /var/lib/mysql
* nginx exposes 3306 port to 5306 local one

### Docker setup commands
* create config file with `cp .env.example .env`
* setup database connection at .env 
* run at ./docker directory: `docker-compose build` to build containers
* run at ./docker directory: `sudo docker-compose up` to start containers
* `sudo docker ps` to list running containers and get containers names
* `sudo docker exec -w /src -it docker_php_1 composer install` to install dependencies(docker_php_1 is php-fpm container name retrieved from docker ps)
* `sudo docker exec -it docker_php_1 php /src/artisan key:generate` to generate application key
* `sudo docker exec -it docker_php_1 php /src/artisan migrate` to run migrations
* `sudo docker exec -it docker_php_1 php /src/artisan db:seed` to seed database
* `sudo docker exec -w /src -it docker_php_1 ./vendor/bin/phpunit` to run test

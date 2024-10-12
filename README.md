## Project installation instruction

### Demo

#### Register Page
![list of users](./assets/register.png)

#### Search Page
![list of users](./assets/search_nid.png)

#### Search Result Page
![list of users](./assets/search_result.png)

### Tools & Tech stack used
- Laravel 11 (back-end)
- Blade.php (front-end)
- Tailwind 2.2 CSS
- MySQL 8 (Database)
- Docker (nginx, php-fpm, mysql, redis, scheduler)

### Run Laravel Project
    git clone https://github.com/riad1302/covid-vaccine-registration.git
    cd covid-vaccine-registration/docker
    cp .env.example .env
    cp docker-compose.override.example.yml docker-compose.override.yml
    cd /.envs
    cp mysql.env.example mysql.env
    cp php-ini.env.example php-ini.env
    cp redis.env.example redis.env
    docker network create kahf-net
    docker-compose build
    docker-compose up -d
    docker-compose exec -it app composer install
    docker-compose exec -it app php artisan key:generate
    docker-compose exec -it app php artisan migrate
    docker-compose exec -it app php artisan test
    docker-compose exec -it app php artisan db:seed

### Check Application
    http://localhost:8000/


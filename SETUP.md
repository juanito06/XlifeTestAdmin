# XLife Admin API — Setup

## Requisitos
- PHP 8.2+ (recomendado)
- Composer 2.x

---

## Windows PowerShell AND VSC

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate --seed

php artisan l5-swagger:generate

php artisan serve

## Linux y macOS

composer install

cp .env.example .env

php artisan key:generate

chmod -R 775 storage bootstrap/cache

php artisan migrate --seed

php artisan l5-swagger:generate

php artisan serve

---

## Verificación servidores en local 

http://localhost:8000/api/documentation#/

http://localhost:8000/

## Tambien Podemos verificar la migracion de datos a la BD 

http://localhost:8000/api/stats/geographic

http://localhost:8000/api/users

http://localhost:8000/api/posts

http://localhost:8000/api/comments

http://localhost:8000/api/reports

http://localhost:8000/api/reports/summary

## asi sucesivamente ir observando que migren correctamente, para conocer el resto de URLs 

http://localhost:8000/api/documentation#/


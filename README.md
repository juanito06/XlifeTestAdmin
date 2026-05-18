XLife Admin API — Setup#
clone the repository
gh repo clone juanito06/XlifeTestAdmin

requirements
PHP 8.2+ (recomendado) Composer 2.x

Windows PowerShell,VSC
composer install

cp .env.example .env

php artisan key:generate

php artisan migrate --seed

php artisan l5-swagger:generate

php artisan serve

Linux y macOS
composer install

cp .env.example .env

php artisan key:generate

chmod -R 775 storage bootstrap/cache

php artisan migrate --seed

php artisan l5-swagger:generate

php artisan serve

verify local servers
http://localhost:8000/api/documentation#/

http://localhost:8000/

verify migration to the database
http://localhost:8000/api/stats/geographic

http://localhost:8000/api/users

http://localhost:8000/api/posts

http://localhost:8000/api/comments

http://localhost:8000/api/reports

http://localhost:8000/api/reports/summary

swagger documentation
http://localhost:8000/api/documentation#/
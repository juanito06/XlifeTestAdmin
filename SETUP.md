# XLife Admin API — Setup

## Requisitos
- PHP 8.2+
- Composer 2.x

---

## Windows (PowerShell)

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan l5-swagger:generate
php artisan serve

## Linux / macOS

composer install
cp .env.example .env
php artisan key:generate
chmod -R 775 storage bootstrap/cache
php artisan migrate --seed
php artisan l5-swagger:generate
php artisan serve

---

## Verificación

| URL | Resultado esperado |
|-----|--------------------|
| http://localhost:8000/api/users | Lista de usuarios paginada |
| http://localhost:8000/api/stats/overview | KPIs en tiempo real |
| http://localhost:8000/api/users/99999 | {"message":"Risorsa non trovata"} |
| http://localhost:8000/api/documentation | Swagger UI interactivo |
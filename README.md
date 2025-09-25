# Laravel-Elmikeev-App

Тестоовое задание Elmekeev на позицию PHP-разработчик

Задача: Вам необходимо стянуть все данные по описанным эндпоинтам и сохранить в БД.
Доступы для БД выгружать в файл README.MD

---

Сервис для выгрузки данных из внешнего API и сохранения их в бд.

## Разворачивание через Docker

1. Построить и запустить контейнеры:

```bash
docker compose up -d --build
docker compose exec app bash
php artisan migrate
```

2. Запустить команду для выгрузки данных:

```bash
php artisan fetch:all 2025-08-01(2000-01-01) 2025-09-01(now)
```

---

# Доступ к БД

DB_CONNECTION=mysql
DB_HOST=db4free.net
DB_PORT=3306
DB_DATABASE=laravelelmikeev2
DB_USERNAME=laravel123
DB_PASSWORD=laravel123
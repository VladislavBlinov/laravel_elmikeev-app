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

2. Создать компанию, аккаунт, сервис, тип токена, токен:

```bash
php artisan add:company "Компания 1"
php artisan add:account 1 "Аккаунт 1"
php artisan add:service "Service" "https://service.ru"
php artisan add:tokentype 1 "api-key"
php artisan add:token 1 1 "api-key" "token"

```

3. Запустить команду для выгрузки данных:

```bash
php artisan fetch:all --account-all
```


# Laravel-Elmikeev-App

Сервис для выгрузки данных из внешнего API и сохранения их в БД.

## Разворачивание через Docker

1. Построить и запустить контейнеры:

```bash
docker compose up -d --build
docker compose exec app bash
php artisan migrate
```

2. Добавить данные:

```bash
php artisan add:company "Company"
php artisan add:account 1 "Account"
php artisan add:service "ServiceName" "https://api.example.ru"
php artisan add:tokentype "api-key"
php artisan add:service-tokentype 1 1
php artisan add:token 1 1 "api-key" "API_KEY"

```

3. Запустить команду для выгрузки данных:

Для одного аккаунта:

```bash
php artisan fetch:all 1 "2025-01-01" "2025-10-02"
```

Для всех аккаунтов:

```bash
php artisan fetch:all --account-all "2025-01-01" "2025-10-02"
```

Даты можно не писать, скрипт автоматически возьмет свежие данные.

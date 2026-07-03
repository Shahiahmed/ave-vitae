# Клиника — внутренняя система записи пациентов

Внутренняя админ-панель для частной клиники (Казахстан). Публичной части нет — вся работа ведётся в панели Filament по адресу `/admin`. Пациент — это запись в базе, а не пользователь системы.

## Стек

- PHP 8.3+, Laravel 13
- Filament 5 (Livewire), spatie/laravel-permission 6
- MySQL 8 / MariaDB (локально можно SQLite)
- Интерфейс: русский / казахский / китайский (переключатель в топбаре)

## Роли

| Роль | Возможности |
|------|-------------|
| `admin` | всё: пользователи, отделения, пациенты, записи, дашборд |
| `operator` | пациенты, записи, «Сегодня», дашборд |
| `reception` | «Сегодня» — смена статусов визита |
| `doctor` | «Мои приёмы» — завершение приёма (только свои записи) |

## Установка (локально)

```bash
composer install
cp .env.example .env
php artisan key:generate
# указать доступ к БД в .env (DB_DATABASE=ave, DB_USERNAME, DB_PASSWORD)
php artisan migrate:fresh --seed
php artisan serve
```

Панель: `http://127.0.0.1:8000/admin` · вход `admin@clinic.kz` / `password`.

## Деплой на прод

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env            # заполнить APP_KEY, БД, APP_URL
php artisan key:generate
php artisan migrate --force
npm ci && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Убедиться, что в `.env`: `APP_ENV=production`, `APP_DEBUG=false`, реальные креды БД. В проде тестовые пользователи не создаются (сидер заводит только `admin@clinic.kz`) — **сразу смените пароль администратора**.

## Автодеплой

Пуш в ветку `main` автоматически разворачивается на сервере через GitHub Actions
(`.github/workflows/deploy.yml` → SSH → `deploy.sh`): `git reset --hard` →
`composer install` → `migrate --force` → `filament:optimize` + кеши → права `www-data`.

Требуются секреты репозитория: `SSH_HOST`, `SSH_USER`, `SSH_PORT`, `SSH_KEY`.

## Тесты

```bash
php artisan test
```

Feature-тесты (Pest) покрывают матрицу прав (роль × ресурс), переходы статусов визита и доступ к страницам.

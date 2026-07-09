# Сервер: как всё настроено (памятка для новых проектов)

Вставь этот файл (или его содержимое) в начало новой сессии, чтобы агент сразу знал окружение
и не ломал соседние сайты.

## Доступ

- **Contabo VPS, Ubuntu 24.04**, вход: `ssh root@185.194.217.14` (пароль).
- Фаервол `ufw` **выключен** — порты снаружи открыты, отдельно ничего разрешать не нужно.

## Что уже крутится (НЕ ТРОГАТЬ)

| Сайт | Путь | nginx | БД |
|------|------|-------|-----|
| `api.phytobiotech.kz` (Laravel) | `/var/www/phytobiotech/backend` | 443 + 80 (Certbot) | MySQL `phytobiotech` |
| Клиника (Laravel + Filament 5) | `/var/www/ave-vitae` | порт **8080** | MySQL `ave` (юзер `ave_user`) |

Новый проект → **новый порт** (например 8081), **новый каталог** в `/var/www/`, **новая БД**.

## Стек на сервере

- **PHP 8.3.6** + PHP-FPM. Сокет: `unix:/var/run/php/php8.3-fpm.sock`.
- Расширения: `bcmath curl fileinfo gd intl mbstring pdo_mysql xml xsl zip` и др.
  ⚠️ `gd` доустанавливали вручную (`apt install php8.3-gd`) — он нужен mPDF.
  Если новый пакет требует расширение — сначала `apt install php8.3-<ext>`, иначе `composer install` упадёт.
- **nginx 1.24**, конфиги в `/etc/nginx/sites-available/`, симлинки в `sites-enabled/`.
- **Composer 2.9.8** в `/usr/local/bin/composer`. **git 2.43**.
- **Node/npm НЕТ** — фронт-сборку на сервере делать нельзя. Для Filament это ок:
  ассеты публикуются через `php artisan filament:assets` / `filament:optimize`.

## MySQL

- Под `root` работает **через сокет без пароля**: просто `mysql -e "SHOW DATABASES;"`.
- Для каждого проекта заводим отдельную БД + отдельного пользователя (не root):
  ```sql
  CREATE DATABASE IF NOT EXISTS <db> CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  CREATE USER IF NOT EXISTS '<user>'@'localhost' IDENTIFIED BY '<pass>';
  GRANT ALL PRIVILEGES ON <db>.* TO '<user>'@'localhost';
  FLUSH PRIVILEGES;
  ```
- Импорт дампа клиента: `mysql <db> < dump.sql`.

## Шаблон nginx для нового проекта (порт 8081)

```nginx
server {
    listen 8081;
    server_name _;
    root /var/www/<project>/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    client_max_body_size 100M;
    index index.php;
    charset utf-8;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
    location ~ /\.(?!well-known).* { deny all; }
}
```
Затем: `ln -s ... /etc/nginx/sites-enabled/` → `nginx -t` → `systemctl reload nginx`.

## Автодеплой (как сделано у клиники — повторить для нового проекта)

- `deploy.sh` в корне репо: `git reset --hard origin/main` → `composer install --no-dev`
  → `php artisan migrate --force` → `filament:optimize-clear` + `filament:optimize`
  → `config:cache` + `view:cache` → `chown -R www-data:www-data` → `systemctl reload php8.3-fpm`.
- `.github/workflows/deploy.yml` (appleboy/ssh-action) запускает `deploy.sh` по SSH при пуше в `main`.
- Секреты репозитория: `SSH_HOST`, `SSH_USER=root`, `SSH_PORT=22`, `SSH_KEY`
  (приватный ключ; публичный — в `/root/.ssh/authorized_keys`).

## Грабли (реально ловили)

1. **НЕ делать `php artisan route:cache`**, если есть closure-роуты — падает.
   `config:cache` и `view:cache` — можно.
2. **`git` под root ругается** на каталог, принадлежащий `www-data`:
   `git config --global --add safe.directory /var/www/<project>`.
3. **Composer под root** требует `export COMPOSER_ALLOW_SUPERUSER=1`.
4. **opcache** — после деплоя `systemctl reload php8.3-fpm` (уже в `deploy.sh`).
5. **Нет расширения PHP → `composer install` падает** и деплой оставляет старые vendor.
   Проверять `php -m` перед добавлением пакетов.
6. Права: `chown -R www-data:www-data <project>`, `chmod -R 775 storage bootstrap/cache`,
   `chmod 640 .env`.
7. Для проекта клиента: не забыть перенести **`storage/app`** (загруженные файлы)
   и выполнить `php artisan storage:link`.

## Чек-лист развёртывания нового проекта

```bash
# 1. код
cd /var/www && git clone <repo> <project> && cd <project>
git config --global --add safe.directory /var/www/<project>
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-dev --optimize-autoloader

# 2. окружение
cp .env.example .env && php artisan key:generate
# правим .env: APP_URL=http://185.194.217.14:8081, DB_*, APP_ENV, APP_DEBUG=false

# 3. база (создать + импортировать дамп клиента)
mysql -e "CREATE DATABASE ..."   # см. выше
mysql <db> < /path/dump.sql
php artisan migrate --force

# 4. storage клиента
# скопировать его storage/app в <project>/storage/app
php artisan storage:link

# 5. сборка/кеши/права
php artisan filament:assets || true
php artisan filament:optimize
php artisan config:cache && php artisan view:cache
chown -R www-data:www-data /var/www/<project>
chmod -R 775 storage bootstrap/cache

# 6. nginx (шаблон выше) + reload
nginx -t && systemctl reload nginx
```

#!/usr/bin/env bash
#
# Деплой панели клиники. Запускается автоматически из GitHub Actions по SSH
# при пуше в ветку main. Можно запускать и вручную: bash /var/www/ave-vitae/deploy.sh
#
set -euo pipefail

APP_DIR="/var/www/ave-vitae"
export COMPOSER_ALLOW_SUPERUSER=1

cd "$APP_DIR"

echo ">>> Забираем последний код из origin/main"
git fetch --all --prune
git reset --hard origin/main

echo ">>> Ставим зависимости"
composer install --no-dev --optimize-autoloader --no-interaction

echo ">>> Миграции (без потери данных)"
php artisan migrate --force

echo ">>> Пересобираем кеши Filament и приложения"
php artisan filament:optimize-clear
php artisan filament:optimize
php artisan config:cache
php artisan view:cache

echo ">>> Права для www-data"
chown -R www-data:www-data "$APP_DIR"

echo ">>> Готово. Текущая версия: $(git rev-parse --short HEAD)"

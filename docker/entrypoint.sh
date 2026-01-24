#!/bin/sh
set -e

cd /var/www/html

# Copy example env if no .env exists
if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

# Install composer dependencies if vendor is missing
if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist
fi

# Generate app key (safe for dev)
php artisan key:generate --force || true

# Ensure storage permissions (best-effort)
chown -R www-data:www-data storage bootstrap/cache || true

# Start the built-in Laravel dev server
php artisan serve --host=0.0.0.0 --port=8000

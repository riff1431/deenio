#!/bin/bash
set -e

# Ensure .env exists so Laravel doesn't complain
if [ ! -f /var/www/html/.env ]; then
    touch /var/www/html/.env
fi

# Create storage directory structure if missing
mkdir -p /var/www/html/storage/framework/{sessions,views,cache} /var/www/html/storage/logs /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure storage link exists
php artisan storage:link --force || true

# Run database migrations
php artisan migrate --force || true

# Cache configs and routes in production
if [ -n "$APP_KEY" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Execute main command
exec "$@"

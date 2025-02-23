#!/bin/bash

chown -R www-data:www-data storage resources routes public tests vendor
# Run composer install if vendor directory is missing
if [ ! -d "vendor" ]; then
    composer install --optimize-autoloader --no-dev
fi
if [ -z "$APP_KEY" ]; then
    php artisan key:generate
fi
php artisan migrate
# Start processes
php-fpm -D && nginx -g "daemon off;"

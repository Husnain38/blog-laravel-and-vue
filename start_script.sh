#!/bin/bash

# Turn on bash's job control
#set -m
#
## Start PHP-FPM in the background
#php-fpm &

# Install Composer dependencies (if not installed)
composer install --no-interaction

# Run Laravel artisan commands
#php artisan migrate
php artisan key:generate
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan storage:link
#php artisan passport:install
# Bring the primary process (php-fpm) back into the foreground
#fg %1


php artisan serve --host=0.0.0.0 --port=$APP_PORT

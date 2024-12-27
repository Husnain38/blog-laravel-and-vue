# Use the official PHP image
FROM php:8.2-fpm

# Set working directory inside container
WORKDIR /var/www

# Install system dependencies (required for Laravel)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql intl xml zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy ini php config files to docker container
COPY docker/php/php.ini $PHP_INI_DIR/conf.d/php.ini
COPY docker/php/php-cli.ini $PHP_INI_DIR/conf.d/php-cli.ini

# Setting permission to ini PHP files
RUN chmod 644 $PHP_INI_DIR/conf.d/php.ini
RUN chmod 644 $PHP_INI_DIR/conf.d/php-cli.ini

# Copy Laravel application files into container
COPY . /var/www
COPY start_script.sh /scripts/start_script.sh
RUN ["chmod", "+x", "/scripts/start_script.sh"]
# Install dependencies
RUN composer install

# Set proper permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["/scripts/start_script.sh"]

# Build Stage
FROM php:8.3-fpm AS builder

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    tzdata \
    libmagickwand-dev --no-install-recommends \
    locales \
    locales-all \
    libreoffice \
    ghostscript \
    nginx \
    telnet \
    net-tools \
    vim

# Install PHP extensions
RUN docker-php-ext-install exif gd intl mysqli pdo_mysql zip bcmath calendar gettext sockets \
    && docker-php-ext-enable exif gd intl mysqli pdo_mysql zip bcmath calendar gettext

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Application setup
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer \
    && composer install --no-scripts --no-autoloader

# Copy the application code
COPY . /var/www/html

# Re-run composer to optimize autoloader
RUN composer dump-autoload --optimize

# Copy nginx configurations
COPY ./docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY ./docker/main-nginx.conf /etc/nginx/nginx.conf

# Set up permissions for entrypoint
RUN chmod +x docker/entrypoint.sh

EXPOSE 80

# Use entrypoint script
CMD ["sh", "docker/entrypoint.sh"]

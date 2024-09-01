# Use the PHP 8.3 image with FPM and Alpine Linux
FROM php:8.3-fpm-alpine

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-install opcache

# Add the OpCache configuration file
ADD opcache.ini $PHP_INI_DIR/conf.d/

# Install necessary dependencies for Composer
RUN apk add --no-cache \
    git \
    unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www/html

# Create a Debian environment with php and apache
FROM php:8.1-apache

# Install debian packages
RUN apt update && \
    docker-php-ext-install mysqli pdo pdo_mysql

RUN apt install -y \
      libzip-dev \
      zip \
    && docker-php-ext-install zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Enable Rewrite
RUN a2enmod rewrite
RUN service apache2 restart;
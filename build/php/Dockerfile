FROM php:8.1-apache

RUN apt update && \
    docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

RUN service apache2 restart

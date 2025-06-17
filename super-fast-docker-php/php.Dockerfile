FROM php:8.0-fpm
RUN pecl install redis \
    && docker-php-ext-enable redis

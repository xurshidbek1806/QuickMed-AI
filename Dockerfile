FROM php:8.4-fpm-alpine

RUN apk add --no-cache postgresql-dev libzip-dev zip unzip git \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm"]

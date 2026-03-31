# ─────────────────────────────────────────
# Stage 1: Build frontend (Vite / Vue 3)
# ─────────────────────────────────────────
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN SKIP_WAYFINDER=1 npm run build

# ─────────────────────────────────────────
# Stage 2: PHP-FPM + Composer
# ─────────────────────────────────────────
FROM php:8.4-fpm-alpine

RUN apk add --no-cache postgresql-dev libzip-dev zip unzip git \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application source
COPY . .

# Copy built Vite assets from stage 1
COPY --from=frontend /app/public/build /var/www/public/build

# Install PHP dependencies (no dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]

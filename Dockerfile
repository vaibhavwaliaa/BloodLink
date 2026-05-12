FROM node:18-alpine AS frontend-build
WORKDIR /app/frontend
COPY frontend/package*.json ./
RUN npm ci
COPY frontend/ ./
RUN npm run build

FROM composer:2 AS dependencies
WORKDIR /app/backend
COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist

FROM php:8.2-fpm
RUN apt-get update && apt-get install -y git zip unzip libssl-dev pkg-config && rm -rf /var/lib/apt/lists/*
RUN pecl install mongodb && docker-php-ext-enable mongodb
WORKDIR /var/www/html
COPY --from=dependencies /app/backend/vendor ./vendor
COPY backend/ ./
COPY --from=frontend-build /app/frontend/dist ./public/spa
RUN chown -R www-data:www-data /var/www/html
EXPOSE 8080
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]

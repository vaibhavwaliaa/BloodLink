# Multi-stage build: PHP + Node for Laravel + frontend build
FROM php:8.2-fpm-alpine AS builder

# Install system dependencies for PHP and build tools
RUN apk add --no-cache \
    nodejs npm \
    git \
    curl \
    ca-certificates \
    zip \
    unzip \
    openssl-dev \
    $PHPIZE_DEPS

# Install Composer using the same PHP binary as the image
RUN curl -fsSL https://getcomposer.org/installer -o /tmp/composer-setup.php \
    && php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm /tmp/composer-setup.php

# Install PHP extensions
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

WORKDIR /app

# Copy entire repo
COPY . .

# Install backend PHP dependencies
WORKDIR /app/backend
RUN composer install --no-dev --optimize-autoloader

# Build frontend
WORKDIR /app/frontend
RUN npm ci && npm run build

# Copy frontend dist to backend public
RUN rm -rf /app/backend/public/spa || true && \
    mkdir -p /app/backend/public/spa && \
    cp -r /app/frontend/dist/* /app/backend/public/spa/

# Final stage: PHP FPM runtime
FROM php:8.2-fpm-alpine

# Install runtime dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    ca-certificates \
    oniguruma \
    openssl \
    $PHPIZE_DEPS

# Install PHP extensions
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Copy PHP config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /app

# Copy built application from builder stage
COPY --from=builder /app/backend /app

# Create necessary directories
RUN mkdir -p /app/storage/logs /app/storage/framework/cache /app/storage/framework/sessions && \
    chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Copy nginx config to Alpine nginx http.d include path
COPY nginx.conf /etc/nginx/http.d/default.conf

# Copy supervisor config
COPY supervisord.conf /etc/supervisord.conf

# Set permissions
RUN chown -R www-data:www-data /app

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

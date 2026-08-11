# =====================================================================
# Crisis Command Center (Laravel) — Dockerfile
# PHP 8.3 + FPM + Composer untuk production
# =====================================================================
FROM php:8.3-fpm-bookworm

# --- System dependencies ---
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip curl libpq-dev libzip-dev libpng-dev libjpeg-dev \
    libfreetype6-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mysqli zip gd bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- Composer ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --- Aplikasi ---
WORKDIR /var/www/html
COPY . .

# --- Dependency install (production, tanpa dev) ---
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress \
    && mkdir -p storage/framework/{cache,sessions,views} \
    && mkdir -p storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache || true

# --- User non-root ---
RUN useradd -m -s /bin/bash ccc \
    && chown -R ccc:www-data /var/www/html
USER ccc

EXPOSE 9000
CMD ["php-fpm"]
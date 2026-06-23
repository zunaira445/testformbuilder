FROM php:8.2-fpm

# 1. System dependencies install karo
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libcurl4-openssl-dev \
    libgd-dev \
    libzip-dev

# 2. PHP extensions install karo
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 3. Composer install karo
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 4. Pehle sirf composer.json copy karo taake dependencies pehle install ho sakein
COPY composer.* ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# 5. Ab baqi ka code copy karo
COPY . .

# 6. Permissions set karo
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8000

# 7. Start command
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
FROM php:8.2-fpm

# System dependencies install karna
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

# PHP extensions install karna
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer install karna
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Permissions set karna
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
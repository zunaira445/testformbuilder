FROM php:8.2-cli

# Dependencies install karo (libonig-dev add kar diya gaya hai)
RUN apt-get update && apt-get install -y \
    libgd-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    curl \
    unzip \
    && docker-php-ext-install gd mbstring xml curl zip pdo pdo_mysql tokenizer \
    && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . .

RUN composer install --optimize-autoloader --no-scripts --no-interaction

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
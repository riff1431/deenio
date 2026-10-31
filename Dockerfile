# ----------------------------------------------------
# Stage 1: Build frontend assets with Node.js
# ----------------------------------------------------
FROM node:20-alpine AS frontend-builder
WORKDIR /app

COPY web/package*.json ./
RUN npm ci || npm install

COPY web/resources ./resources
COPY web/public ./public
COPY web/vite.config.js ./
RUN npm run build

# ----------------------------------------------------
# Stage 2: Production PHP 8.3 with Apache
# ----------------------------------------------------
FROM php:8.3-apache

WORKDIR /var/www/html

# Install required system dependencies & PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Configure Apache virtual host pointing to public/
COPY web/apache.conf /etc/apache2/sites-available/000-default.conf

# Copy Composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy web application code
COPY web/ /var/www/html

# Copy pre-compiled frontend assets from Stage 1
COPY --from=frontend-builder /app/public/build /var/www/html/public/build

# Install PHP dependencies for production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set up permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod +x /var/www/html/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
CMD ["apache2-foreground"]

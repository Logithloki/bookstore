# Dockerfile
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    libzip-dev \
    libicu-dev \
    gnupg \
    default-mysql-client

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install MongoDB extension (version 1.21.0)
RUN pecl install mongodb-1.21.0 \
    && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Copy package.json and package-lock.json for npm dependencies
COPY package*.json ./

# Install npm dependencies with clean cache
RUN npm cache clean --force && \
    npm ci --no-optional && \
    npm install --force

# Install dependencies
RUN composer install --no-dev --no-scripts --no-autoloader

# Copy the rest of the application
COPY . .

# Build assets (for production)
RUN npm run build || echo "Build skipped for development"

# Create Laravel cache directories
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

# Complete composer installation
RUN composer dump-autoload --no-dev --optimize

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set proper permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Configure Apache document root
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]


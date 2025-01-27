FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy entire project first
COPY . .

# Install Composer dependencies
RUN composer install --no-scripts

# Install npm packages
RUN npm cache clean --force
RUN npm install
RUN npm install vite @vitejs/plugin-react laravel-vite-plugin --save-dev

# Now run composer scripts after all files are in place
RUN composer dump-autoload --optimize
RUN php artisan package:discover --ansi

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache 
# Use official PHP 8.3 image with Apache
FROM php:8.3-apache

# Install system dependencies needed for extensions and Composer
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql zip mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod expires

# Set working directory
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-interaction --optimize-autoloader

COPY . .

# Copy Apache configuration
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# This script will run on container start to fix file permissions.
COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set permissions for the Apache user. This sets the base permissions in the image.
# The entrypoint script will handle the final permissions for mounted volumes.
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# --- NEW: Set the entrypoint ---
ENTRYPOINT ["entrypoint.sh"]

# Start Apache (this command is passed to the entrypoint)
CMD ["apache2-foreground"]
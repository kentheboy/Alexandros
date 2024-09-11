# Use an official PHP image with Apache
FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd

# Enable Apache mod_rewrite (required by Laravel)
RUN a2enmod rewrite

# Copy the existing Laravel project files to the container
COPY ../ /var/www/html

# Copy custom php.ini configuration
COPY ./YingZheng/php.ini /usr/local/etc/php/

# Set the correct permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Apache configuration
COPY ./YingZheng/000-default.conf /etc/apache2/sites-available/000-default.conf


# Expose port 80 to be able to access the application
EXPOSE 80

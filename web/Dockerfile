FROM php:8.2-apache

# Install PHP extensions for MySQL/MariaDB
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache rewrite module (optional, but good practice)
RUN a2enmod rewrite

# Copy application files
COPY ./app/ /var/www/html/

# Set ownership for web files (Apache runs as www-data)
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
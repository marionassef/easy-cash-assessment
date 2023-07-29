FROM php:8.1-fpm


# Set working directory
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN apt-get update && apt-get install -y --no-install-recommends git zip unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader && \
    apt-get remove -y git zip unzip && \
    apt-get autoremove -y && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copy the rest of the application files
COPY . .

# Set file permissions (optional, depending on your setup)
RUN chown -R www-data:www-data /var/www/html/storage

# Expose port 8000 (adjust if your app uses a different port)
EXPOSE 8000

# Start PHP-FPM
CMD ["php-fpm"]

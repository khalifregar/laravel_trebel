FROM php:8.2-cli

# Install dependencies PHP + mysql-client
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    default-mysql-client \
    && docker-php-ext-install zip pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy semua file project Laravel ke dalam container
COPY . .

# Install dependency Laravel
RUN composer install

# Expose port Laravel serve
EXPOSE 8000

# Copy entrypoint untuk auto migrate + serve
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Jalanin entrypoint
CMD ["/entrypoint.sh"]

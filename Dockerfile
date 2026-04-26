# Stage 1: Build stage
FROM php:8.3-fpm AS builder

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    sqlite3 \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    bcmath \
    ctype \
    fileinfo \
    json \
    mbstring \
    tokenizer

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist

# Stage 2: Final stage
FROM php:8.3-fpm

# Set environment
ENV DEBIAN_FRONTEND=noninteractive

# Set working directory
WORKDIR /app

# Install runtime dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    sqlite3 \
    libsqlite3-dev \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    bcmath \
    ctype \
    fileinfo \
    json \
    mbstring \
    tokenizer

# Copy PHP config
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Create app user
RUN useradd -G www-data,root -u 1000 -d /home/appuser -m -s /bin/bash appuser

# Copy application from builder
COPY --from=builder --chown=appuser:www-data /app /app

# Copy remaining application files
COPY --chown=appuser:www-data . /app

# Create necessary directories
RUN mkdir -p storage/logs storage/app storage/framework/cache storage/framework/sessions storage/framework/views \
    && chown -R appuser:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Create database directory
RUN mkdir -p database && chown -R appuser:www-data database

# Switch to appuser
USER appuser

# Expose port
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:9000/ping || exit 1

# Entry point
COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]

# Stage 1: Build stage
FROM php:8.4-fpm AS builder

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    autoconf \
    pkg-config \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    unzip \
    git \
    curl \
    wget \
    sqlite3 \
    libsqlite3-dev \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

# --- AJUSTE AQUI: Instalação do Redis no Builder ---
RUN pecl install redis && docker-php-ext-enable redis

# Install PHP extensions individually
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j1 gd zip curl pdo pdo_mysql pdo_sqlite bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --prefer-dist

# Stage 2: Final stage
FROM php:8.4-fpm

ENV DEBIAN_FRONTEND=noninteractive
WORKDIR /app

# Install runtime dependencies
RUN apt-get update && apt-get install -y \
    autoconf \
    pkg-config \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    sqlite3 \
    libsqlite3-dev \
    curl \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

# --- AJUSTE AQUI: Instalação do Redis na imagem Final ---
RUN pecl install redis && docker-php-ext-enable redis

# Install PHP extensions individually
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j1 gd zip curl pdo pdo_mysql pdo_sqlite bcmath

COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

RUN useradd -G www-data,root -u 1000 -d /home/appuser -m -s /bin/bash appuser

COPY --from=builder --chown=appuser:www-data /app /app

# Copy only necessary config files
COPY --chown=appuser:www-data docker/ /app/docker/
COPY --chown=appuser:www-data .env.example /app/.env.example
COPY --chown=appuser:www-data app/ /app/app/
COPY --chown=appuser:www-data database/ /app/database/
COPY --chown=appuser:www-data routes/ /app/routes/
COPY --chown=appuser:www-data resources/ /app/resources/
COPY --chown=appuser:www-data config/ /app/config/
COPY --chown=appuser:www-data public/ /app/public/
COPY --chown=appuser:www-data artisan /app/artisan
COPY --chown=appuser:www-data bootstrap/ /app/bootstrap/

RUN mkdir -p /app/storage/framework/sessions /app/storage/framework/views /app/storage/framework/cache/data /app/bootstrap/cache && \
    chown -R appuser:www-data /app && \
    chmod -R 775 /app/storage /app/bootstrap/cache && \
    chmod +x /app/artisan || true

COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

USER appuser
EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
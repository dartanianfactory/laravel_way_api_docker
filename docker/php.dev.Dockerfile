FROM php:8.2-fpm

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache fileinfo

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /app
RUN chown -R www:www /app

WORKDIR /app

USER www
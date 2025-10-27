# syntax=docker/dockerfile:1

FROM composer:2 AS vendor
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

FROM node:20 AS frontend
WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.* tailwind.config.* postcss.config.* ./
COPY resources resources
RUN npm run build

FROM php:8.3-fpm AS app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        ca-certificates \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libwebp-dev \
        libicu-dev \
        libzip-dev \
        libonig-dev \
        libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        gd \
        intl \
        pcntl \
        pdo_mysql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /var/www/html/vendor vendor
COPY --from=vendor /var/www/html/composer.json composer.json
COPY --from=vendor /var/www/html/composer.lock composer.lock
COPY --from=frontend /var/www/html/public/build public/build

RUN rm -f bootstrap/cache/*.php public/hot

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]

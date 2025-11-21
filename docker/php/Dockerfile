FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash git unzip \
    nodejs npm \
    libpng-dev libjpeg-turbo-dev libwebp-dev libxpm-dev \
    icu-dev oniguruma-dev libzip-dev $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install pdo_mysql gd intl mbstring zip bcmath exif opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# タイムゾーン
RUN ln -snf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && echo "Asia/Tokyo" > /etc/timezone

WORKDIR /var/www

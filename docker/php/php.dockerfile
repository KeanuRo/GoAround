FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libonig-dev \
    libpq-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    nano \
    curl \
    libcurl4-openssl-dev pkg-config libssl-dev

RUN docker-php-ext-install pdo pdo_pgsql && docker-php-ext-install pgsql  \
    && docker-php-ext-install mbstring exif pcntl zip \
    && docker-php-ext-configure gd --enable-gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

EXPOSE 9000
CMD ["php-fpm"]
FROM php:8-fpm

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql && docker-php-ext-install pgsql

EXPOSE 9000
CMD ["php-fpm"]
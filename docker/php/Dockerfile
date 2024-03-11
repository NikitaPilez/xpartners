FROM php:8.2-fpm

RUN apt-get update && apt-get install -y libpq-dev git unzip
RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /code

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash -s -- --install-dir=/usr/bin
RUN chmod +x /usr/bin/symfony
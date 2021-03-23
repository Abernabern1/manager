FROM php:7.4-cli

RUN apt-get update && apt-get install libzip-dev -y && apt-get install -y libpq-dev zlib1g-dev zip \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql zip

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php

WORKDIR /app
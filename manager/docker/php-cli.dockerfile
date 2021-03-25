FROM php:7.4-cli

RUN apt-get update && apt-get install -y libmcrypt-dev mariadb-client wget unzip \
    && docker-php-ext-install pdo_mysql pcntl

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php

WORKDIR /app
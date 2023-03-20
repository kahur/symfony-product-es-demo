FROM php:8.1-fpm

RUN apt-get update -y
RUN apt-get install curl libcurl4-openssl-dev libzip-dev libpng-dev zlib1g-dev git -y

RUN docker-php-ext-install pdo_mysql gd zip curl
# install php redis
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    && docker-php-ext-enable redis
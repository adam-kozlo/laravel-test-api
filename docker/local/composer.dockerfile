FROM composer:latest

RUN apk --no-cache add shadow && usermod -u 1000 www-data

RUN apk --no-cache add zlib-dev libpng-dev libxml2-dev \
     && docker-php-ext-install gd soap

FROM php:8.2-fpm-alpine

RUN apk --no-cache add shadow && usermod -u 1000 www-data
RUN echo 'memory_limit = 3048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'upload_max_filesize = 10M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'max_execution_time = 60' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

RUN apk --no-cache add zlib-dev libpng-dev libxml2-dev zip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql gd soap zip



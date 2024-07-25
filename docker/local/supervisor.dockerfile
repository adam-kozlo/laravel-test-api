FROM php:8.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN apk update && apk add --no-cache supervisor
RUN mkdir -p "/etc/supervisor/logs"

COPY docker/local/supervisor/default.conf /etc/supervisor/supervisord.conf
COPY docker/local/supervisor/crontab /etc/crontabs/root

CMD ["/usr/bin/supervisord", "-n", "-c","/etc/supervisor/supervisord.conf"]
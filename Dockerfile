FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git     curl     zip     vim  unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql

COPY ./src /var/www/html/

EXPOSE 8080

CMD [ "php-fpm" ]
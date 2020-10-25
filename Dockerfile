FROM php:7.4-cli AS base
EXPOSE 80
RUN apt update && apt install zip libzip-dev -y && docker-php-ext-install zip && docker-php-ext-enable zip
WORKDIR /php
COPY ./php/composer.phar ./php/composer.json ./php/composer.lock /php/
RUN php composer.phar install --prefer-dist --no-dev --no-autoloader && rm -rf /root/.composer
CMD ["php", "-S", "0.0.0.0:80", "src/gateway.php"]

FROM base AS prod
COPY ./php /php
RUN php composer.phar dump-autoload --no-dev --optimize

FROM base AS testbase
COPY ./util/xdebug.so /usr/local/lib/php/extensions/no-debug-non-zts-20190902/

FROM testbase AS dev
RUN docker-php-ext-enable xdebug

FROM testbase AS test
RUN php composer.phar install --prefer-dist --no-autoloader && rm -rf /root/.composer
COPY ./php /php
RUN php composer.phar dump-autoload --optimize && docker-php-ext-enable xdebug
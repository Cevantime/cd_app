FROM php:7.4-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get update \
    && apt-get install -y --no-install-recommends apt-utils git libicu-dev g++ libpng-dev libzip-dev;

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
    mv composer.phar /usr/local/bin/composer

COPY php.ini /usr/local/etc/php/php.ini
COPY vhosts/app.conf /etc/apache2/sites-enabled/000-default.conf
COPY www/app /var/www/app

ARG APP_ENV=dev
ARG APP_DEBUG=1
ARG DATABASE_URL=mysql://root:helloworld@db:3306/app?serverVersion=5.7

RUN echo ${APP_ENV}

RUN docker-php-ext-install pdo pdo_mysql gd opcache intl zip calendar

RUN addgroup --system symfony --gid 1000 && adduser --system symfony --uid 1000 --ingroup symfony

WORKDIR /var/www/app

RUN mkdir -p var && \
    DBURL=$(echo $DATABASE_URL | sed -e "s/\//\\\\\\//g") && \
    sed -e "s/DATABASE_URL=.*/DATABASE_URL=$DBURL/" .env > .env.local && \
    composer install --no-dev --optimize-autoloader && \
    APP_ENV=$APP_ENV APP_DEBUG=$APP_DEBUG bin/console cache:clear && \
    chown -R www-data:www-data var && \
    bin/console doctrine:migration:migrate --no-interaction && \
    bin/console doctrine:fixtures:load --no-interaction
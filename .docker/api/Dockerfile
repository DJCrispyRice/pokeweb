FROM php:8.3-apache
# User apache id
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Installing & enabling required modules
RUN a2enmod rewrite
RUN apt-get update && apt-get -y install git libicu-dev libpq-dev libzip-dev sudo zip
RUN pecl install xdebug-3.3.0 && docker-php-ext-enable xdebug
RUN docker-php-ext-install intl pgsql pdo_pgsql zip
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN service apache2 start

RUN mkdir /var/www/.composer && chown -R www-data: /var/www/.composer

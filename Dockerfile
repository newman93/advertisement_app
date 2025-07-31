FROM php:8.3-apache

RUN apt-get update && \
    apt-get install -y git unzip libicu-dev libzip-dev libonig-dev libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-install intl pdo pdo_mysql zip gd

# Ustaw DocumentRoot na /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN git config --global --add safe.directory /var/www/html

RUN composer install

RUN a2enmod rewrite

EXPOSE 80
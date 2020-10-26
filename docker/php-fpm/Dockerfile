FROM php:7.4.6-fpm

RUN apt-get update && apt-get install -y zlib1g-dev libpq-dev git libicu-dev libxml2-dev libfreetype6-dev libmcrypt-dev libjpeg-dev libpng-dev libzip-dev unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install opcache \
    && docker-php-ext-install zip xml \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-enable opcache \
    && docker-php-ext-install soap

RUN docker-php-ext-configure gd \
    && docker-php-ext-install gd \
    && docker-php-ext-enable gd

RUN curl --insecure https://getcomposer.org/composer-stable.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Warsaw /etc/localtime
RUN "date"

RUN pecl install apcu && docker-php-ext-enable apcu

# install xdebug
#RUN pecl install xdebug \
#&& docker-php-ext-enable xdebug \
#&& echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#&& echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#&& echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#&& echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#&& echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#&& echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#&& echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN printf '[PHP]\ndate.timezone = "Europe/Warsaw"\n' > /usr/local/etc/php/conf.d/tzone.ini

WORKDIR /var/www/symfony

FROM php:8.2-fpm
USER root

RUN apt-get update && apt-get install -y \
      apt-utils \
      libpq-dev \
      curl \
      libpng-dev \
      iptables-persistent \
      libzip-dev \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      zip unzip \
      iputils-ping \
      git \
      nodejs \
      npm && \
      docker-php-ext-install pdo_mysql && \
      docker-php-ext-install bcmath && \
      docker-php-ext-configure gd --with-freetype --with-jpeg && \
      docker-php-ext-install gd && \
      docker-php-ext-install zip && \
      docker-php-ext-install exif && \
      pecl install xdebug && \
      docker-php-ext-enable xdebug && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \

#COPY ./.docker/php/conf.d/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./.docker/php/conf.d/php.ini /usr/local/etc/php.ini
COPY ./.docker/php/conf.d/php.ini /usr/local/etc/php/php.ini

COPY ./.docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN rm /usr/local/etc/php-fpm.d/www.conf
COPY ./.docker/php/conf.d/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./.docker/php/conf.d/www.conf /usr/local/etc/php/conf.d

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

EXPOSE 8080

WORKDIR /var/www

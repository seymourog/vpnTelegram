FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
      apt-utils \
      libpq-dev \
      libpng-dev \
      libzip-dev \
      zip unzip \
      git && \
      docker-php-ext-install pdo_mysql && \
      docker-php-ext-install bcmath && \
      docker-php-ext-install gd && \
      docker-php-ext-install zip && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./.docker/php/conf.d/php.ini /usr/local/etc/php/conf.d/php.ini

COPY app            /var/www/vpnTelegram/app
COPY bootstrap      /var/www/vpnTelegram/bootstrap
COPY config         /var/www/vpnTelegram/config
COPY database       /var/www/vpnTelegram/database
COPY public         /var/www/vpnTelegram/public
COPY resources      /var/www/vpnTelegram/resources
COPY routes         /var/www/vpnTelegram/routes
COPY storage        /var/www/vpnTelegram/storage
COPY artisan        /var/www/vpnTelegram/artisan
COPY package.json   /var/www/vpnTelegram/package.json
#COPY webpack.mix.js /var/www/vpnTelegram/webpack.mix.js

COPY composer.*  ./

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin
# alias
RUN echo "alias a='artisan'" >> /root/.bashrc

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install -y nodejs

RUN composer install \
      --no-interaction \
      --no-plugins \
      --no-suggest \
      --no-scripts \
      --no-autoloader \
      --prefer-dist

RUN composer dump-autoload  --no-scripts --optimize && \
    chown -R root:www-data /var/www/vpnTelegram && \
    chmod 755 -R /var/www/vpnTelegram && \
    chmod -R 775 /var/www/vpnTelegram/storage && \
    chmod -R 775 /var/www/vpnTelegram/bootstrap/cache

EXPOSE 8080

WORKDIR /var/www/vpnTelegram

FROM php:8.3-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH=$HOME/.composer/vendor/bin:$PATH

# Download script to install PHP extensions and dependencies
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

# install system utilities
RUN apk add --no-cache \
    curl \
    nano \
    zip unzip \
    linux-headers

# Install dev libraries
RUN apk add --no-cache \
    icu-dev \
    libpq-dev \
    libxml2-dev \
    unixodbc-dev \
    librdkafka-dev

# Install PHP
RUN install-php-extensions \
    bz2 \
    pcntl \
    intl \
    mbstring \
    zip \
    sockets \
    xdebug \
    @composer

# Configure PHP
COPY conf/conf.d/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY conf/php.development.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/html

CMD sleep infinity

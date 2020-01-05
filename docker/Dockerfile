FROM php:7.2-fpm-alpine

ENV BUILD_DEPS \
        make \
		gcc \
        autoconf \
        libc-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        icu-dev

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk update && \
    apk add --no-cache \
        git \
        zip \
        freetype \
        libjpeg-turbo \
        libpng \
        icu-libs \
        $BUILD_DEPS && \
    docker-php-ext-configure \
        gd --with-freetype-dir=/usr/include --with-jpeg-dir=/usr/include && \
    docker-php-ext-install \
        gd \
        opcache \
        pdo_mysql \
        pcntl \
        intl \
        zip && \
    pecl install \
        apcu && \
    docker-php-ext-enable \
        apcu && \
    apk del $BUILD_DEPS && \
    rm -rf /var/cache/apk/* /tmp/* /var/tmp/* && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /srv

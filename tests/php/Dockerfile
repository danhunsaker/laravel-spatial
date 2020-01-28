ARG PHP_VERSION=7.4
FROM php:${PHP_VERSION}

RUN set -ex \
        && apt-get update -q \
        && apt-get install -yq --no-install-recommends libpq-dev \
        && rm -rf /var/lib/apt/lists/* \
        && docker-php-ext-install -j "$(nproc)" pdo pdo_mysql pdo_pgsql \
        && apt-get clean

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
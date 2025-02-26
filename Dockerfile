FROM php:8.2 AS php

RUN apt-get update -y \
    && apt-get install -y unzip libpq-dev libcurl4-gnutls-dev libsodium-dev \
    && docker-php-ext-install pdo pdo_mysql bcmath \
    && pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# Install latest Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
COPY . .

ENV PORT=8000
ENTRYPOINT [ "docker/entrypoint.sh" ]

# ==============================================================================
# Node.js stage
FROM node:23-alpine AS node

WORKDIR /var/www
COPY . .

RUN npm install --global cross-env \
    && npm install

VOLUME /var/www/node_modules

# syntax=docker/dockerfile:1

# Image situs docs. FrankenPHP (PHP 8.4 + Caddy) menyajikan public/ di port 80.
#
# Tahap `vendor` memasang dependency PHP. Tahap `assets` mem-build aset Vite dan
# butuh vendor/, karena app.css dan app.js mengimpor kit dan Livewire dari sana.
# Tahap `app` merakit image akhir tanpa Node dan tanpa dependency dev.

FROM dunglas/frankenphp:1-php8.4 AS base
RUN install-php-extensions intl opcache zip
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app

FROM base AS vendor
COPY composer.json composer.lock ./
# Kit dipasang dari path repository, jadi paketnya harus ada sebelum install.
COPY packages ./packages
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts --no-autoloader

FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

FROM base AS app
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
RUN composer dump-autoload --no-dev --optimize \
    && cp docker/php.ini "$PHP_INI_DIR/conf.d/zz-app.ini"

EXPOSE 80
CMD ["sh", "docker/start.sh"]

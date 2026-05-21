FROM mlocati/php-extension-installer:latest AS installer
FROM php:8.2-cli-bookworm

COPY --from=installer /usr/bin/install-php-extensions /usr/bin/
RUN apt-get update && apt-get install -y --no-install-recommends git unzip \
    && install-php-extensions mongodb \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock* ./

RUN composer install --no-dev --prefer-dist --no-interaction --no-audit --ignore-platform-reqs --no-autoloader

COPY . .

RUN composer dump-autoload --no-dev --optimize

EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t public"]
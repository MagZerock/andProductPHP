FROM php:8.2-cli-alpine

RUN apk update && apk add --no-interactive --no-cache \
        git \
        unzip \
        openssl-dev \
        pcre-dev \
        icu-dev \
        $PHPIZE_DEPS \
    && pecl install mongodb-1.16.2 \
    && docker-php-ext-enable mongodb \
    && apk del $PHPIZE_DEPS \
    && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock* ./

RUN composer install --no-dev --prefer-dist --no-interaction --no-audit --ignore-platform-reqs --no-autoloader

COPY . .

RUN composer dump-autoload --no-dev --optimize

EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t public"]
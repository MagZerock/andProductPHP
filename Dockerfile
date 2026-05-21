FROM php:8.2-cli-bookworm

# 1. Instalar dependencias del sistema y herramientas de compilación
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libssl-dev \
        pkg-config \
        libcurl4-openssl-dev \
        zlib1g-dev \
    && apt-get install -y --no-install-recommends $PHPIZE_DEPS \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/lib/apt/lists/*

# 2. Copiar Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3. Configurar el directorio de trabajo
WORKDIR /app

# 4. Copiar archivos de dependencias de PHP e instalarlas
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 5. Copiar el resto del código de la aplicación
COPY . .

# 6. Exponer el puerto por defecto
EXPOSE 10000

# 7. Comando de arranque usando la variable de entorno PORT (requerido por Render)
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t public"]
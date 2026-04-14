FROM php:8.2-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY app/composer.json app/composer.lock ./
RUN composer install --no-dev --no-interaction

COPY app/ ./
RUN composer dump-autoload --optimize

COPY railway-router.php /app/railway-router.php

ENV PORT=8080

EXPOSE 8080

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t /app/public /app/railway-router.php"]

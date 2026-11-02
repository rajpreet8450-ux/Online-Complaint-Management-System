FROM dunglas/frankenphp:1-php8.4-alpine

WORKDIR /app

RUN install-php-extensions pdo_mysql pdo_sqlite \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY Caddyfile /etc/caddy/Caddyfile
COPY . /app

ENV APP_ENV=production \
    APP_DB_DRIVER=mysql \
    PORT=80

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]

FROM webdevops/php-nginx:8.3-alpine

RUN apk add --no-cache oniguruma-dev libxml2-dev \
 && docker-php-ext-install bcmath ctype fileinfo mbstring pdo_mysql xml

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN apk add --no-cache nodejs npm

ENV WEB_DOCUMENT_ROOT=/app/public
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-dev --no-scripts

COPY . .
RUN npm ci || npm install && npm run build || true

RUN chown -R application:application /app
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["supervisord"]

#!/usr/bin/env sh
set -e

: "${DB_HOST:=db}"
: "${DB_PORT:=3306}"
: "${DB_CONNECTION:=mysql}"
: "${SKIP_DB_WAIT:=0}"

if [ "$DB_CONNECTION" = "sqlite" ] || [ "$SKIP_DB_WAIT" = "1" ]; then
  echo "Skipping DB wait (DB_CONNECTION=$DB_CONNECTION SKIP_DB_WAIT=$SKIP_DB_WAIT)"
else
  echo "Waiting for database at ${DB_HOST}:${DB_PORT} ..."
  ATTEMPTS=60
  while ! nc -z "${DB_HOST}" "${DB_PORT}"; do
    ATTEMPTS=$((ATTEMPTS-1))
    [ "$ATTEMPTS" -le 0 ] && echo "DB timeout" && exit 1
    sleep 2
  done
  echo "Database is up."
fi

if [ -f .env ] && ! grep -q '^APP_KEY=' .env; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force
fi

mkdir -p storage/framework/cache
mkdir -p storage/framework/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs storage/app/public bootstrap/cache
touch storage/logs/laravel.log || true
chown -R application:application storage bootstrap/cache || true
chmod -R ug+rwX storage bootstrap/cache || true

php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

php artisan migrate --force || true
php artisan storage:link || true

exec /entrypoint supervisord

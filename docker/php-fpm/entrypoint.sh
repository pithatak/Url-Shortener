#!/bin/sh
set -e

echo "Waiting for PostgreSQL..."

until pg_isready -h postgres -p 5432 -U "$POSTGRES_USERNAME"; do
  sleep 1
done

echo "PostgreSQL is ready"

echo "🧹 Clearing cache..."
php bin/console cache:clear || true

# Composer install
echo "Running composer install..."
composer install --no-interaction --prefer-dist

echo "Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction || true

echo "Starting application..."
exec "$@"

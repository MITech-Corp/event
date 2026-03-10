#!/bin/sh
set -e

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate
fi

php artisan migrate --force 2>/dev/null || true

exec "$@"

#!/bin/sh
set -e

APP_DIR="/var/www/html"
ENV_FILE="${APP_DIR}/.env"
ENV_EXAMPLE="${APP_DIR}/.env.example"

# Ensure an .env file exists so artisan commands can modify it.
if [ ! -f "${ENV_FILE}" ]; then
    if [ -f "${ENV_EXAMPLE}" ]; then
        cp "${ENV_EXAMPLE}" "${ENV_FILE}"
    else
        touch "${ENV_FILE}"
    fi
fi

# Make sure the PHP user owns the env file so Laravel can write to it.
chown www-data:www-data "${ENV_FILE}"

# Ensure storage and cache directories exist with correct permissions.
mkdir -p "${APP_DIR}/storage/logs" "${APP_DIR}/bootstrap/cache"

# Guarantee the main log file exists to avoid runtime permission errors.
LOG_FILE="${APP_DIR}/storage/logs/laravel.log"
if [ ! -f "${LOG_FILE}" ]; then
    touch "${LOG_FILE}"
fi

# Allow the www-data user (and group) to read/write cache and storage.
chown -R www-data:www-data "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache"
chmod -R u+rwX,g+rwX "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache"

# Generate an application key if it's missing or malformed.
APP_KEY_VALUE="$(grep '^APP_KEY=' "${ENV_FILE}" | head -n1 | cut -d '=' -f2- | tr -d '\r')"
if ! printf '%s' "${APP_KEY_VALUE}" | grep -Eq '^base64:[A-Za-z0-9+/=]{43,45}$'; then
    su -s /bin/sh www-data -c "cd ${APP_DIR} && php artisan key:generate --force --no-interaction"
fi

exec "$@"

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

exec "$@"

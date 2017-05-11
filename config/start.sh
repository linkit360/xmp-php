#!/usr/bin/env bash

# Migrations && Run
set -ex \
    && php /app/yii migrate/up --interactive=0 \
    && php-fpm --fpm-config /usr/local/etc/php-fpm.conf

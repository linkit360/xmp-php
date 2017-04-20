#!/usr/bin/env bash

# Migrations
php /app/php/yii migrate/up --interactive=0
if [ $? != 0 ]; then
    printf "\n\n FAIL \n\n"
    exit 1
fi

# PHP-FPM Run
php-fpm --fpm-config /usr/local/etc/php-fpm.conf

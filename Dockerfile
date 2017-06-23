FROM php:7.1.6-fpm-alpine

# Deps
RUN set -ex \
    && apk add --progress --no-cache --virtual deps \
        # Soft
        bash wget curl nano git openssl \
        # UTF-8
        icu-libs icu-dev \
        # Libs
        libmcrypt-dev libxml2-dev postgresql-dev \
    # PHP Ext
    && docker-php-ext-install pdo pdo_pgsql mbstring json intl sockets mcrypt iconv zip soap xml

# Env
EXPOSE 9000
ENV TERM xterm

# Add Group and User
RUN set -ex \
    && addgroup -S -g 1000 docker \
    && adduser -S -D -s /bin/bash -u 1000 -G docker docker

# Composer
ENV COMPOSER_HOME /composer/home
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY composer/ /composer
RUN set -ex \
    && chown -R 1000:1000 /composer \
    && chmod -R 0700 /composer

# REMEMBER: only RUN under non-root USER, not COPY
USER 1000
RUN composer global require -n -vv --prefer-dist "hirak/prestissimo:^0.3" "fxp/composer-asset-plugin:^1.3.1"
USER root

# Configs
COPY config/ /config
RUN set -ex \
    && mkdir -p /usr/local/etc/php \
    && mv /config/php.ini /usr/local/etc/php/php.ini \
    && mv /config/php-fpm.ini /usr/local/etc/php-fpm.conf \
    && mv /config/php-www.ini /usr/local/etc/php-fpm.d/www.conf \
    && cp /config/.bashrc /home/docker \
    && mv /config/.bashrc /root \
    && chown -R 1000:1000 /usr/local/etc/php && chmod -R 0700 /usr/local/etc/php

# App
RUN set -ex \
    && mkdir -p /app/common \
    && mkdir -p /app/console \
    && mkdir -p /app/frontend

# common
COPY app/codeception.yml /app
COPY app/GetSubInfo.wsdl /app
COPY app/yii /app
COPY app/common /app/common
RUN chown -R 1000:1000 /app && chmod -R 0700 /app

# console
COPY app/console /app/console
RUN chown -R 1000:1000 /app/console && chmod -R 0700 /app/console

# frontend
COPY app/frontend /app/frontend
RUN chown -R 1000:1000 /app/frontend && chmod -R 0700 /app/frontend

# User switch
WORKDIR /app
USER 1000
ENTRYPOINT ["bash", "/config/start.sh"]

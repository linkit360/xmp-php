FROM php:7.1.3-fpm-alpine

# Env
EXPOSE 9000
ENV TERM xterm
ENV COMPOSER_HOME /app/composer/home

# Deps
RUN apk add --progress --no-cache \
    bash wget curl nano \
    icu-libs icu-dev \
    git zip unzip zlib-dev \
    openssl libmcrypt-dev libxml2-dev \
    sudo su-exec postgresql-dev

# Add Group and User
RUN addgroup -S -g 1000 docker
RUN adduser -S -D -s /bin/bash -u 1000 -G docker docker

# PHP Ext
RUN docker-php-ext-install opcache mbstring pdo pdo_pgsql json intl sockets mcrypt iconv zip soap
RUN docker-php-ext-install xml

# Composer
RUN mkdir -p /app/composer/home
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY composer/ /app/composer
RUN chown -R 1000:1000 /app/composer && chmod -R 0700 /app/composer

# REMEMBER: only RUN under non-root USER, not COPY
USER 1000
RUN composer -n global require --prefer-dist "hirak/prestissimo:^0.3" "fxp/composer-asset-plugin:^1.2.0"
USER root

# Configs
COPY config/ /app/config
RUN mv /app/config/php.ini /usr/local/etc/php-fpm.conf
RUN mv /app/config/php-www.ini /usr/local/etc/php-fpm.d/www.conf
RUN cp /app/config/.bashrc /home/docker
RUN mv /app/config/.bashrc /root

# App
RUN mkdir -p /app/php/common
RUN mkdir -p /app/php/console
RUN mkdir -p /app/php/frontend

# common
COPY app/codeception.yml /app/php
COPY app/GetSubInfo.wsdl /app/php
COPY app/yii /app/php
COPY app/common /app/php/common
RUN chown -R 1000:1000 /app/php && chmod -R 0700 /app/php

# console
COPY app/console /app/php/console
RUN chown -R 1000:1000 /app/php/console && chmod -R 0700 /app/php/console

# frontend
COPY app/frontend /app/php/frontend
RUN chown -R 1000:1000 /app/php/frontend && chmod -R 0700 /app/php/frontend

# User switch
WORKDIR /app/php
USER 1000
ENTRYPOINT ["bash", "/app/config/start.sh"]

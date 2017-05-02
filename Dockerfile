FROM php:7.1.4-fpm-alpine

# Deps
RUN apk add --progress --no-cache \
    # System soft
    bash wget curl nano git openssl \
    # UTF-8
    icu-libs icu-dev \
    # PHP Deps
    libmcrypt-dev libxml2-dev postgresql-dev

# PHP Ext
RUN docker-php-ext-install opcache mbstring pdo pdo_pgsql json intl sockets mcrypt iconv zip soap xml

# Env
EXPOSE 9000
ENV TERM xterm

# Add Group and User
RUN addgroup -S -g 1000 docker
RUN adduser -S -D -s /bin/bash -u 1000 -G docker docker

# Composer
RUN mkdir -p /composer/home
ENV COMPOSER_HOME /composer/home
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY composer/ /composer
RUN chown -R 1000:1000 /composer && chmod -R 0700 /composer

# REMEMBER: only RUN under non-root USER, not COPY
USER 1000
RUN composer global require -n -vv --prefer-dist "hirak/prestissimo:^0.3" "fxp/composer-asset-plugin:^1.2.0"
USER root

# Configs
COPY config/ /config
RUN mv /config/php.ini /usr/local/etc/php-fpm.conf
RUN mv /config/php-www.ini /usr/local/etc/php-fpm.d/www.conf
RUN cp /config/.bashrc /home/docker
RUN mv /config/.bashrc /root

# App
RUN mkdir -p /app/common
RUN mkdir -p /app/console
RUN mkdir -p /app/frontend

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

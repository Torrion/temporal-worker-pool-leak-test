FROM php:8.1.3-cli-alpine3.14


RUN echo "date.timezone = UTC"         > /usr/local/etc/php/conf.d/etc.ini
ENV TERM xterm

# Install required packages
RUN apk add --update --no-cache \
libstdc++ \
libcurl \
bash \
curl \
libxml2-dev \
libzip-dev \
git \
&& rm -rf /var/lib/apt/lists/*

# Build required php modules
RUN apk add --no-cache --virtual .build-deps \
    build-base \
    linux-headers \
    gcc \
    autoconf

ENV PROTOBUF_VERSION "3.21.1"
RUN pecl channel-update pecl.php.net \
    && MAKEFLAGS="-j $(nproc)" pecl install protobuf-${PROTOBUF_VERSION} grpc \
    && docker-php-ext-enable protobuf grpc

RUN docker-php-ext-install -j$(nproc) sockets

RUN apk del .build-deps


#Runner env setup

COPY --from=spiralscout/roadrunner:2.10.3 /usr/bin/rr /bin/rr

# PHP project setup
COPY --from=composer:2.1.6 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY *.json *.lock ./

RUN composer check-platform-reqs \
    && composer install \
        --dev \
        --prefer-dist \
        --no-scripts \
        --optimize-autoloader \
        --no-interaction \
    && composer clear-cache

COPY . /app

ENTRYPOINT /bin/rr serve -d -c /app/.rr.yaml

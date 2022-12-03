FROM debian:11.0-slim

RUN apt-get update \
    && apt install -y --no-install-recommends \
        curl \
        ca-certificates \
        gnupg \
    && curl -s https://packages.sury.org/php/apt.gpg | gpg --dearmor > /usr/share/keyrings/deb.sury.org-php.gpg \
    && echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php bullseye main" > /etc/apt/sources.list.d/sury.list

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        procps \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

ARG PHP_VERSION

# Replace ${PHP_VERSION} by 8.1 hardcoded to achieve the build of the container.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        php${PHP_VERSION}-apcu \
        php${PHP_VERSION}-bcmath \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-common \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-iconv \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-mysql \
        php${PHP_VERSION}-uuid \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Fake user to maps with the one on the host
#ARG USER_ID
RUN addgroup --gid 1000 app && \
    adduser --system --uid 1000 --home /home/app --shell /bin/bash app

# Configuration
COPY php-base/php-configuration /etc/php/${PHP_VERSION}

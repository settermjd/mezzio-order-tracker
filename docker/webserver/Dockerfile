ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-rc-apache-bookworm AS base

FROM base AS development

WORKDIR /var/www/html

# Enable custom error reporting
COPY ./docker/php/conf.d/error.ini /usr/local/etc/php/conf.d/20-error.ini

# Add the PDO PgSQL driver required to interact with the app's PostgreSQL database
RUN set -eux; \
    apt-get update && \
    apt-get install -y --no-install-recommends \
            libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql && \
    rm -rf /var/lib/apt/lists/*

# Set up Composer
COPY --from=composer:2.2.7 /usr/bin/composer /usr/bin/

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY ./docker/php/docker-entrypoint /usr/local/bin/

ENTRYPOINT ["docker-entrypoint"]

CMD ["apache2-foreground"]

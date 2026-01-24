FROM php:8.2-cli

RUN apt-get update \
  && apt-get install -y --no-install-recommends unzip git libzip-dev zip \
  && docker-php-ext-install zip \
  && rm -rf /var/lib/apt/lists/*

# Install composer binary from official composer image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html || true

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

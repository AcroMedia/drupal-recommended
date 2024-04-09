FROM uselagoon/php-8.2-cli-drupal:latest

# Copy auth.* so that local works but doesn't break lagoon (where this file won't exist)
COPY composer.* auth.* /app/
COPY assets /app/assets

RUN COMPOSER_MEMORY_LIMIT=-1 composer self-update
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev

COPY . /app

RUN mkdir -p -v -m775 /app/web/sites/default/files

# Define where the Drupal Root is located
ENV WEBROOT=[WEBROOT]

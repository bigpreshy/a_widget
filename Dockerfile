FROM php:8.2-cli-alpine #

# Install common PHP extensions (add more as needed)
# zip, intl are often useful for Composer and PHPUnit
RUN apk add --no-cache $PHPIZE_DEPS icu-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files (or use a volume in docker-compose)
# COPY . /app

# User for running commands (optional, good practice)
# RUN addgroup -g 1000 myuser && adduser -u 1000 -G myuser -s /bin/sh -D myuser
# USER myuser

ENTRYPOINT ["composer"] # Optional: make composer the default entrypoint
FROM php:8.0-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libicu-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    build-essential \
    openssl \
    nginx \
    wget \
    netcat

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure opcache --enable-opcache
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd opcache

RUN echo "error_reporting=E_ALL" > /usr/local/etc/php/conf.d/php.ini && \
    echo "display_errors=1" >> /usr/local/etc/php/conf.d/php.ini  && \
    echo "log_errors=1" >> /usr/local/etc/php/conf.d/php.ini  && \
    echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/php.ini && \
    echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/php.ini

RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.enable_cli=1'; \
        echo 'opcache.validate_timestamps=\${OPCACHE_VALIDATE_TIMESTAMPS}'; \
        echo 'opcache.revalidate_freq=0'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.max_accelerated_files=5000'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.interned_strings_buffer=16'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"

RUN mkdir -p /run/nginx

WORKDIR /app
COPY . .

RUN ls -la

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN chown -R www-data:www-data /app
RUN chmod -R 755 /app/storage

RUN composer install --working-dir="/app"
RUN composer dump-autoload --working-dir="/app"

RUN php artisan route:clear
RUN php artisan route:cache
RUN php artisan config:clear
RUN php artisan config:cache

EXPOSE 80

RUN ["chmod", "+x", "docker/post_deploy.sh"]

CMD [ "sh", "./docker/post_deploy.sh" ]

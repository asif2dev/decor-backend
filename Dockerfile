FROM php:8-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

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
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN echo "error_reporting=E_ALL" > /usr/local/etc/php/conf.d/php.ini && \
    echo "display_errors=1" >> /usr/local/etc/php/conf.d/php.ini  && \
    echo "log_errors=1" >> /usr/local/etc/php/conf.d/php.ini  && \
    echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/php.ini && \
    echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/php.ini

# Create system user to run Composer and Artisan Commands
# RUN useradd -G www-data,root -u $uid -d /home/$user $user
# RUN mkdir -p /home/$user/.composer && \
#     chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

# USER $user

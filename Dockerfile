FROM php:7.1-fpm

WORKDIR /var/www

RUN docker-php-ext-install pdo pdo_mysql zip unzip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin && \
        echo "alias composer='composer'" >> /root/.bashrc && \
        composer
FROM php:8.2-cli
WORKDIR /var/www
RUN apt-get update && apt-get install -y unzip && docker-php-ext-install pdo_mysql
COPY . .
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
CMD ["php", "-S", "0.0.0.0:8000"]
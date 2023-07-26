FROM php:7.4
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && apt-get install zip unzip default-mysql-server libmcrypt-dev apt-transport-https gnupg -y
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install symfony-cli -y
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /materials/
COPY . /materials/
RUN composer install
RUN chmod +x startup-script.sh

# RUN bin/console app:import-ontology
# RUN bin/console cache:clear && symfony server:start
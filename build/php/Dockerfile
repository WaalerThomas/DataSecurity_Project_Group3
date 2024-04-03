FROM php:8.1-apache

RUN apt-get update && docker-php-ext-install mysqli pdo pdo_mysql

# Enable mod_rewrite and mod_headers
RUN a2enmod rewrite
RUN a2enmod headers

# Enable site
COPY datasec.conf /etc/apache2/sites-available/
RUN a2ensite datasec.conf
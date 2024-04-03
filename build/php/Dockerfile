FROM php:8.1-apache

RUN apt-get update && docker-php-ext-install mysqli pdo pdo_mysql

# Enable mod_rewrite and mod_headers
RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/ && \
    cp /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/

# Enable site
COPY datasec.conf /etc/apache2/sites-available/
RUN a2ensite datasec.conf
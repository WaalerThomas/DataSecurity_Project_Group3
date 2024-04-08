FROM php:8.1-apache

RUN apt-get update && docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get install git -y

# Enable mod_rewrite and mod_headers
RUN a2enmod rewrite
RUN a2enmod headers

# Enable site
COPY datasec.conf /etc/apache2/sites-available/
RUN a2ensite datasec.conf

# Install composer for PHP dependencies
RUN cd /tmp && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
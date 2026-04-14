FROM php:8.4-apache

# Installiere erforderliche Pakete und PHP-Erweiterungen
RUN apt-get update && apt-get install -y \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        exif \
        pdo_mysql \
        mysqli \   
        zip 

# Apache-Modul aktivieren
RUN a2enmod rewrite
FROM php:8.1.18-apache

# Enable Apache rewrite module for .htdacsess
RUN a2enmod rewrite

# Copy Composer binary to the PATH
#COPY --from=composer:2.5.5 /usr/bin/composer /usr/local/bin/composer

# Install MySQLi extension
RUN apt update -y && docker-php-ext-install mysqli

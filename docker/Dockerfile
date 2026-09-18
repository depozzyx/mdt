FROM php:7.4-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite headers
# mod_expires intentionally NOT enabled: .htaccess sets ~120-day caching for
# js/css via mod_expires, which fights local dev rebuilds. mod_headers stays
# enabled so the unguarded "Header set" at the bottom of .htaccess doesn't 500.

COPY docker/no-cache.conf /etc/apache2/conf-available/no-cache.conf
RUN a2enconf no-cache

# Replaces the base image's default vhost to add the uploads proxy-redirect below.
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

FROM php:7.4-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite headers

# GD so WordPress can generate the theme's cropped image sizes (pr-prev, us-prev, g-h...) for
# images added locally by tools/content-pages.php. Without it new attachments only have the
# full-size file and every card would load the original.
# The php:7.4 image is Debian bullseye, which has moved to archive.debian.org.
RUN printf 'deb http://archive.debian.org/debian bullseye main\ndeb http://archive.debian.org/debian-security bullseye-security main\n' > /etc/apt/sources.list \
    && rm -f /etc/apt/sources.list.d/*.list /etc/apt/sources.list.d/*.sources \
    && apt-get -o Acquire::Check-Valid-Until=false update \
    && apt-get install -y --no-install-recommends libjpeg62-turbo-dev libpng-dev libwebp-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*
# mod_expires intentionally NOT enabled: .htaccess sets ~120-day caching for
# js/css via mod_expires, which fights local dev rebuilds. mod_headers stays
# enabled so the unguarded "Header set" at the bottom of .htaccess doesn't 500.

COPY docker/no-cache.conf /etc/apache2/conf-available/no-cache.conf
RUN a2enconf no-cache

# Replaces the base image's default vhost to add the uploads proxy-redirect below.
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

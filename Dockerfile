FROM php:5.6.30-apache

RUN echo deb http://httpredir.debian.org/debian jessie-backports main contrib non-free >>/etc/apt/sources.list

RUN apt-get update && apt-get install -y ffmpeg libcurl4-openssl-dev php5-mysql

RUN  docker-php-ext-install pdo pdo_mysql mysqli curl mysql fileinfo

RUN a2enmod rewrite

COPY docker/php.ini /usr/local/etc/php.ini

COPY docker/vhost.conf /etc/apache2/sites-enabled/analytics.conf

COPY . /var/www/html/analytics

RUN mkdir -p /var/www/html/analytics/frontend/web/videos/data/

RUN chmod 777 /var/www/html/analytics/frontend/web/videos/data/

EXPOSE 80
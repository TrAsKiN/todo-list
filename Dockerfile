FROM php:5.5-apache

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
ADD https://raw.githubusercontent.com/php/php-src/PHP-5.5/php.ini-development /usr/local/etc/php/php.ini

RUN chmod +w /usr/local/etc/php/php.ini
RUN sed -ri -e 's|;date.timezone =|date.timezone = Europe/Paris|g' /usr/local/etc/php/php.ini
RUN sed -ri -e 's!/var/www/html!/var/www/web!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/web!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -ri -e 's!ServerTokens OS!ServerTokens Prod!g' /etc/apache2/conf-available/security.conf
RUN sed -ri -e 's!ServerSignature On!ServerSignature Off!g' /etc/apache2/conf-available/security.conf
RUN sed -i -e '$ a ServerName 127.0.0.1' /etc/apache2/apache2.conf
RUN chmod +x /usr/local/bin/install-php-extensions
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt-get update -q
RUN apt-get install wget ca-certificates symfony-cli -y
RUN install-php-extensions xdebug pdo pdo_pgsql intl mbstring zip opcache
RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet
RUN a2enmod rewrite
RUN service apache2 restart
RUN apt-get autoremove --purge
RUN apt-get clean

WORKDIR /var/www/

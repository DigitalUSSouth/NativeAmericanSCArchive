FROM ubuntu:16.04
RUN apt-get update && apt-get install -y \
    apache2 \
    curl \
    php7.0 \
    php7.0-mysql \
    php7.0-xml \
    php7.0-curl \
    libapache2-mod-php7.0
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
RUN service apache2 start
RUN a2enmod rewrite
RUN service apache2 restart
EXPOSE 80
CMD apachectl -D FOREGROUND


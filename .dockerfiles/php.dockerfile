FROM php:7.2-fpm
#RUN printf "deb http://archive.debian.org/debian/ jessie main\ndeb-src http://archive.debian.org/debian/ jessie main\ndeb http://security.debian.org jessie/updates main\ndeb-src http://security.debian.org jessie/updates main" > /etc/apt/sources.list
#RUN apt-get update && apt-get install -y mysql-client --no-install-recommends \ && docker-php-ext-install pdo_mysql

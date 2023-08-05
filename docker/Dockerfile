FROM nginx:1.18

MAINTAINER Phong Hai "phong.vh@lancsnet.com"

RUN apt update && apt upgrade -y && apt-get -y install nginx-extras

FROM nouchka/sqlite3:latest

RUN apt-get update && apt-get -y install libsqlite3-dev

FROM php:7.4-fpm

#Install xdebug
RUN pecl install xdebug-2.9.8 && docker-php-ext-enable xdebug

#Install php package
RUN apt update && apt install libcurl4-openssl-dev libonig-dev libxml2-dev -y libsqlite3-dev && docker-php-ext-install curl session sockets filter json gettext bcmath pdo pdo_sqlite mbstring xml xdebug

CMD ["php-fpm"]


FROM thecodingmachine/php:8.2-v4-apache-node18

USER root
RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen pt_BR.UTF-8 \
    && update-locale LANG=pt_BR.UTF-8

USER docker

# Setar variável de ambiente para pt_BR.UTF-8
ENV LANG pt_BR.UTF-8

#COPY src/ /var/www/html/

ENV PHP_EXTENSIONS="gd"

# COPY ./php.ini /usr/local/etc/php/conf.d/custom.ini

ENV APACHE_DOCUMENT_ROOT=public/
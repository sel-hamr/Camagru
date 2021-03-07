FROM php:7.2-apache 

RUN docker-php-ext-install pdo_mysql

#mail
RUN apt update -yq && apt install -yq\ 
        sendmail\
        msmtp\
        mailutils

RUN apt install -yq ca-certificates

COPY ./php.ini /usr/local/etc/php/php.ini
COPY ./msmtprc /etc/msmtprc
RUN echo 'sendmail_path="msmtp -a default -t"' >> /usr/local/etc/php/conf.d/php-sendmail.ini
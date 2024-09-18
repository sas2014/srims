FROM php:8.2-fpm

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install ctype
RUN docker-php-ext-install iconv
#RUN docker-php-ext-install pcre
RUN docker-php-ext-install session
#RUN docker-php-ext-install simplexml
#RUN docker-php-ext-install tokenizer
RUN pecl install xdebug && docker-php-ext-enable xdebug


RUN echo "xdebug.mode=coverage"; \
    echo "xdebug.start_with_request=yes"; \
    echo "xdebug.client_host=host.docker.internal"; \
    echo "xdebug.client_port=9000"; \
>> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;

#{ \
#    echo "xdebug.mode=debug"; \
#    echo "xdebug.start_with_request=yes"; \
#    echo "xdebug.client_host=host.docker.internal"; \
#    echo "xdebug.client_port=9000"; \
#} > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \

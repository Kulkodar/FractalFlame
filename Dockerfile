FROM phpearth/php:7.3-cli

RUN apk add --no-cache php7.3-dev composer bash php7.3-gd

RUN pecl install xdebug \
    rm -rf /tmp/pear

RUN echo "zend_extension=/usr/lib/php/7.3/modules/xdebug.so" >> /etc/php/7.3/php.ini \
    && echo "xdebug.remote_enable=1" >> /etc/php/7.3/php.ini

WORKDIR "/app"
ENTRYPOINT "/bin/bash"

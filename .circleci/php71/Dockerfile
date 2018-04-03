FROM php:7.1-cli

WORKDIR /php

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN pecl install xdebug-2.6.0
RUN echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20160303/xdebug.so" >> /usr/local/etc/php/conf.d/xdebug.ini

CMD ["tail", "-f", "/dev/null"]

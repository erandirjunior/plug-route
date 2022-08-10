FROM webdevops/php-apache:7.4

COPY ./90-xdebug.ini "{$PHP_INI_DIR}"/conf.d

RUN pear config-set php_ini $PHP_INI_DIR/php.ini

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
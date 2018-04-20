FROM quay.io/aptible/php:5.6

WORKDIR /app

ADD composer.json /app/
ADD composer.lock /app/
RUN composer install --no-ansi --no-interaction --no-scripts --no-autoloader

ADD . /app
RUN composer install --no-ansi --no-interaction
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public/frontend/images

RUN rm -rf /var/www/html && ln -s /app/public /var/www/html
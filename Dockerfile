FROM php:8.1

WORKDIR /var/www/html

ENTRYPOINT ["php", "answer.php"]
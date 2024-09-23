docker compose up -d

docker compose -f docker\development\compose.yml up -d

docker compose -f docker\test\compose.yml up -d

./composer.phar require symfony/serializer-pack

./composer.phar require nelmio/cors-bundle

cd /usr/local/etc/php/conf.d/

php ./bin/phpunit

php /var/www/html/bin/phpunit

php -dxdebug.mode=coverage /var/www/html/bin/phpunit --coverage-html ./var/coverage

php /var/www/html/bin/phpunit --coverage-html ./var/coverage

php /var/www/html/bin/console make:entity

php /var/www/html/bin/console make:migration

php /var/www/html/bin/console doctrine:migrations:migrate

php /var/www/html/bin/console doctrine:fixtures:load

ng serve --open

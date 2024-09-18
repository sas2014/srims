docker compose -f docker\development\compose.yml up -d

docker compose -f docker\test\compose.yml up -d

php /var/www/html/bin/console doctrine:migrations:migrate

php /var/www/html/bin/console doctrine:fixtures:load

php -dxdebug.mode=coverage /var/www/html/bin/phpunit --coverage-html ./var/coverage

#!/bin/sh
set -e

if [ $# -gt 0 ]
then
    exec "$@"
    exit $?
fi


if [ ! -d './vendor' ]
then
    echo 'Vendor does not exists. Running composer install'
    composer install --no-interaction --no-scripts
    composer --no-interaction --no-cache auto-scripts -vvv
fi

if [ -d './var' ]
then
    chmod -R 777 /var
fi

# Run migrations
echo 'Migrations started'
bin/console doctrine:database:create --if-not-exists
bin/console doctrine:migrations:migrate --no-interaction
echo 'Migrations finished'

echo 'load initial Fake data'
bin/console doctrine:fixtures:load --no-interaction

echo 'tests Fixtures and running tests'
bin/console doctrine:database:create --if-not-exists --env=test
bin/console doctrine:schema:update --env=test --force
bin/console doctrine:fixtures:load --env=test --no-interaction
echo 'Fixtures finished'

# Inicia o PHP-FPM
exec php-fpm
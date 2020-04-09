# This script will copy all files into release to build project based on config
cp -R mkdcore/initialize/* release;
composer install;
# /usr/bin/php composer.phar install;
cp -R vendor release;
cp -R composer.json release;
cp -R composer.lock release;

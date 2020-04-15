# This script will copy release to prod. Copy this script don't use this one
cd ..;
cp -r ./release/* /var/www/devbluegable.manaknightdigital.com/htdocs;
cd /var/www/devbluegable.manaknightdigital.com/htdocs;
chown -R www-data:www-data *;
chmod -R 755 *;
chmod -R 777 uploads;
# This script will copy release to prod. Copy this script don't use this one
cp -R release /var/www/devflashbid.manaknightdigital.com/htdocs;
chown -R www-data:www-data *;
chmod -R 755 *;
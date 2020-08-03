echo "Enter Release\n";
# cp ../release/application/config/config.php .
# cp ../release/application/config/database.php .
cd ../release;
echo "Copy Code\n";
rm -rf ../../prod_repo/*
cp -R * ../../prod_repo;
echo "Copy Configuration\n";
cp ../scripts/config.php ../../prod_repo/application/config;
cp ../scripts/database.php ../../prod_repo/application/config;
cd ../../prod_repo;
chmod -R 775 *;
chown -R www-data:www-data *;
echo "Committing\n";
# do git init to folder at start
# sudo ssh-keygen
# Add deploy key to repo
# sudo nano /etc/nginx/common/php74.conf
# fastcgi_param DB_USER "";
# fastcgi_param DB_DATABASE "";
# fastcgi_param DB_PASSWORD "";
# sudo nginx -t
# sudo service nginx restart
git add .;
git commit -a -m "Update $(date +"%D %T")";
git push origin master;
echo "Done\n";
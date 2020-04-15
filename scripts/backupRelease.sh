# This script will copy release into release_old and remove all old files in release
rm -rf ../release_old;
cp -R ../release ../release_old;
cd ../release;
rm -rf *;
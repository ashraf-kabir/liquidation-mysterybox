cd ../release;
rm -rf *;
cd ../;
cp -R mkdcore/initialize/* release;
cp -R vendor release;
cp -R composer.json release;
cp -R composer.lock release;
cd release;
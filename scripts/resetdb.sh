cd release;
php vendor/bin/phinx rollback -e development -t 0;
php vendor/bin/phinx migrate -e development;
php vendor/bin/phinx seed:run -e development;
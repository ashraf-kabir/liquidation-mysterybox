cd release;
/usr/bin/php vendor/bin/phinx rollback -e development -t 0
/usr/bin/php vendor/bin/phinx migrate -e development
/usr/bin/php vendor/bin/phinx seed:run -e development
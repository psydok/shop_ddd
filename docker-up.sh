docker-compose build
docker-compose up -d
docker-compose exec admin_panel bash
docker-compose exec postgres createdb -U admin admindb (usersdb)
docker-compose exec admin_panel yii migrate
composer install --ignore-platform-reqs
vendor/bin/phinx migrate -e development

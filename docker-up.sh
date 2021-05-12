docker-compose build
docker-compose up -d

docker-compose exec postgres createdb -U admin admindb
docker-compose exec postgres createdb -U admin usersdb

docker-compose exec admin_panel yii migrate
docker-compose exec gateway vendor/bin/phinx migrate -e development

composer install --ignore-platform-reqs

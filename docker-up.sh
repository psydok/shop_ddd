docker-compose build
docker-compose up -d
docker-compose exec postgres createdb -U postgres admindb
docker-compose exec admin_panel yii migrate


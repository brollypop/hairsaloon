#!/usr/bin/env bash
cd ../app

docker-compose up -d --build

echo "Waiting for MYSQL to be up and running."
sleep 15

docker exec -it api apt-get update
docker exec -it api apt-get install git
docker exec -it api php composer.phar install
docker exec -it api php bin/console doctrine:schema:create

cd ../scripts
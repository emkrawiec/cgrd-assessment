# start compose
docker-compose up -d

# run migrations inside container
docker cp ../src/backend/sql/init.sql cgrd-db:/tmp/init.sql
docker exec -it cgrd-db /bin/bash -c "mariadb -u cgrd -pcgrd cgrd < /tmp/init.sql"
docker exec -it cgrd-dev-composer /bin/bash -c "composer install"
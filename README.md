docker compose --build

docker exec -it mssql-server /usr/src/app/setup_sql_server.sh

docker exec -it php /bin/bash
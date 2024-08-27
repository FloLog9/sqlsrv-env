FROM mcr.microsoft.com/mssql/server:2022-latest

# Ajouter le script à l'image Docker
COPY ./setup_sql_server.sh /usr/src/app/setup_sql_server.sh
USER root
RUN chmod +x /usr/src/app/setup_sql_server.sh
USER mssql

ENV ACCEPT_EULA=Y
ENV SA_PASSWORD=12345ABcd
ENV MSSQL_PID=Developer

EXPOSE 1433

CMD ["/opt/mssql/bin/sqlservr"]
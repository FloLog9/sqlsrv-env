#!/bin/bash

# Paramètres
SQL_SERVER="localhost,1433"  # Remplacez par l'adresse de votre serveur SQL Server
SQL_USER="sa"           # Remplacez par votre nom d'utilisateur SQL Server
SQL_PASSWORD="12345ABcd"  # Remplacez par votre mot de passe SQL Server
DATABASE_NAME="BULLHORN12146"  # Remplacez par le nom de la base de données à restaurer
CERTIFICATE_NAME="BULLHORN12146.cer"  # Remplacez par le nom du certificat
CERTIFICATE_FILE="/home/mssql/backup/BULLHORN12146.cer"  # Chemin vers le fichier .cer
PRIVATE_KEY_FILE="/home/mssql/backup/BULLHORN12146.pvk"  # Chemin vers le fichier .plk
BACKUP_FILE="/home/mssql/backup/BULLHORN12146.bak"  # Chemin vers le fichier .bak
PRIVATE_KEY_PASSWORD="64121NROHLLUB"

DATA_FILE_PRIMARY="/var/opt/mssql/data/${DATABASE_NAME}_PRIMARY.MDF"
DATA_FILE_DATA="/var/opt/mssql/data/${DATABASE_NAME}_DATA.NDF"
DATA_FILE_INDEX="/var/opt/mssql/data/${DATABASE_NAME}_INDEX.NDF"
DATA_FILE_FTINDEX="/var/opt/mssql/data/${DATABASE_NAME}_FTINDEX.ndf"
LOG_FILE="/var/opt/mssql/data/${DATABASE_NAME}_LOG.LDF"

# Se connecter au serveur SQL Server et activer le certificat
/opt/mssql-tools18/bin/sqlcmd -S $SQL_SERVER -U $SQL_USER -P $SQL_PASSWORD -C -Q "
USE master;
CREATE MASTER KEY ENCRYPTION BY PASSWORD = '12345ABcd';
CREATE CERTIFICATE [$CERTIFICATE_NAME]
FROM FILE = '$CERTIFICATE_FILE'
WITH PRIVATE KEY (
    FILE = '$PRIVATE_KEY_FILE',
    DECRYPTION BY PASSWORD = '$PRIVATE_KEY_PASSWORD'
);
"

# Restaurer la base de données
/opt/mssql-tools18/bin/sqlcmd -S $SQL_SERVER -U $SQL_USER -P $SQL_PASSWORD -C -Q "RESTORE DATABASE [$DATABASE_NAME]
FROM DISK = '$BACKUP_FILE'
WITH 
MOVE 'BULLHORN_PRIMARY' TO '$DATA_FILE_PRIMARY',
MOVE 'BULLHORN_DATA' TO '$DATA_FILE_DATA',
MOVE 'BULLHORN_INDEX' TO '$DATA_FILE_INDEX',
MOVE 'BULLHORN_FTINDEX' TO '$DATA_FILE_FTINDEX',
MOVE 'BULLHORN_LOG' TO '$LOG_FILE',
REPLACE;"

echo "La base de données a été restaurée et le certificat a été activé."
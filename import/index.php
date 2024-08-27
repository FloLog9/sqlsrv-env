<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration de la connexion
$serverName = "mssql"; // Remplacez par le nom de votre serveur
$connectionOptions = array(
    "Database" => "BULLHORN12146", // Remplacez par le nom de votre base de données
    "Uid" => "sa", // Remplacez par votre nom d'utilisateur
    "PWD" => "12345ABcd", // Remplacez par votre mot de passe
    "Encrypt" => true, // Utilisation de l'encryptage (si nécessaire)
    "TrustServerCertificate" => true // Désactivation de la vérification de certificat
);

// Connexion au serveur SQL
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Vérifiez si la connexion a réussi
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Définir la requête SQL
$sql = "SELECT TOP 10 * FROM [bullhorn1].[BH_Candidate]"; // Remplacez par votre requête SELECT


// Exécuter la requête
$stmt = sqlsrv_query($conn, $sql);

// Vérifiez si la requête a réussi
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Définir la requête SQL pour obtenir les noms des colonnes de la table
$tableName = "BH_Candidate"; // Remplacez par le nom de votre table
$sqlColumns = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ?";

// Préparer la requête pour obtenir les noms des colonnes
$params = array($tableName);
$stmtColumns = sqlsrv_query($conn, $sqlColumns, $params);

// Vérifiez si la requête a réussi
if ($stmtColumns === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Récupérer les noms des colonnes
$columns = [];
while ($row = sqlsrv_fetch_array($stmtColumns, SQLSRV_FETCH_ASSOC)) {
    $columns[] = $row['COLUMN_NAME'];
}

// Libérer les ressources pour la requête des colonnes
sqlsrv_free_stmt($stmtColumns);

// Ouvrir un fichier CSV en mode écriture
$csvFileName = "csv/candidates.csv";
$csvFile = fopen($csvFileName, 'w');

// Vérifier si le fichier a été ouvert correctement
if ($csvFile === false) {
    die("Erreur lors de l'ouverture du fichier CSV en écriture.");
}

// Écrire l'en-tête du CSV
fputcsv($csvFile, $columns, ';');

// Parcourir les résultats et les écrire dans le fichier CSV
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Initialiser un tableau pour contenir les données de la ligne actuelle
    $csvData = array();
    foreach ($row as $col) {
        if ($col instanceof DateTime) {
            // Formater l'objet DateTime
            $csvData[] = $col->format('Y-m-d H:i:s');
        } else {
            // Ajouter d'autres valeurs
            $csvData[] = $col;
        }
    }
    // Écrire la ligne dans le fichier CSV
    fputcsv($csvFile, $csvData, ';');
}

// Fermer le fichier CSV
fclose($csvFile);

// Libérer les ressources et fermer la connexion
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>

echo 'Hello World!';
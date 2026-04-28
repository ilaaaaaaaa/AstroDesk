<?php
require_once __DIR__ . '/vendor/autoload.php';

$client = new MongoDB\Client("mongodb+srv://10933944_db_user:FgexGbZaVLpq0pC2@cluster0.stxn4ru.mongodb.net/?appName=Cluster0");
$db = $client->astrodesk;

echo "Connessione riuscita!";
?>
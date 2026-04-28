<?php
require_once __DIR__ . '/../vendor/autoload.php';

$lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

$client = new MongoDB\Client($_ENV['MONGODB_URI']);
$db = $client->astrodesk;

try {
    $collection = $db->test;
    $collection->findOne();
    echo "Connessione OK!";
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}
?>
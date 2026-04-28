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
?>
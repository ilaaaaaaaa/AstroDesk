<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/db.php';

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

// ======================
// MQTT
// ======================

$server = '127.0.0.1';
$port = 1883;
$clientId = 'php-mqtt-subscriber';

$mqtt = new MqttClient($server, $port, $clientId);

$connectionSettings = (new ConnectionSettings);

$mqtt->connect($connectionSettings, true);

echo "Connesso a MQTT\n";

// ======================
// MongoDB
// ======================

$collection = $db->sensori;

echo "Connesso a MongoDB Atlas\n";

// ======================
// Subscribe
// ======================

$mqtt->subscribe('astrodesk/sensori', function ($topic, $message) use ($collection) {

    echo "Messaggio ricevuto:\n";
    echo $message . "\n";

    // Decodifica JSON
    $data = json_decode($message, true);

    if (!$data) {
        echo "JSON non valido\n";
        return;
    }

    // Documento MongoDB
    $document = [
        'temperatura' => $data['temperatura'],
        'umidita' => $data['umidita'],
        'timestamp' => new MongoDB\BSON\UTCDateTime()
    ];

    // Inserimento
    $result = $collection->insertOne($document);

    echo "Inserito documento ID: ";
    echo $result->getInsertedId() . "\n";

}, 0);

echo "In ascolto sul topic astrodesk/sensori...\n";

// Loop infinito
$mqtt->loop(true);

$mqtt->disconnect();
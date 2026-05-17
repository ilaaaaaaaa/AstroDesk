<?php
// ============================================================
//  api/push_display.php — AstroDesk
//  Pubblica i dati astronomici sul broker Mosquitto
//  usando la stessa libreria PhpMqtt del subscriber
// ============================================================

require __DIR__ . '/../vendor/autoload.php';

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

header('Content-Type: application/json');

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['errore' => 'Metodo non consentito']);
    exit;
}

// Legge e valida il body JSON
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || !isset($data['ora']) || !isset($data['alba']) || !isset($data['luna_fase'])) {
    http_response_code(400);
    echo json_encode(['errore' => 'Dati mancanti o JSON non valido']);
    exit;
}

// Sanificazione
$payload = [
    'ora' => (string) ($data['ora'] ?? '--:--'),
    'data' => (string) ($data['data'] ?? '-- --- ----'),
    'alba' => (string) ($data['alba'] ?? '--:--'),
    'tramonto' => (string) ($data['tramonto'] ?? '--:--'),
    'luna_fase' => (string) ($data['luna_fase'] ?? '—'),
    'luna_illum' => (int) ($data['luna_illum'] ?? 0),
];

$payloadJson = json_encode($payload, JSON_UNESCAPED_UNICODE);

// Pubblicazione via PhpMqtt
// ID client diverso dal subscriber per non creare conflitti
try {
    $mqtt = new MqttClient('127.0.0.1', 1883, 'php-mqtt-publisher');
    $mqtt->connect(new ConnectionSettings(), true);
    $mqtt->publish('astrodesk/display', $payloadJson, 0);
    $mqtt->disconnect();

    echo json_encode(['ok' => true, 'payload' => $payload]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['errore' => $e->getMessage()]);
}
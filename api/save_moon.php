<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['illuminazione']) || !isset($data['fase'])) {
    http_response_code(400);
    exit;
}

$collection = $db->moon_phases;

// Controlla se esiste già un record per oggi
$oggi = new MongoDB\BSON\UTCDateTime(strtotime('today') * 1000);
$esistente = $collection->findOne(['data' => $oggi]);

if (!$esistente) {
    $collection->insertOne([
        'data' => $oggi,
        'illuminazione' => (float) $data['illuminazione'],
        'fase' => (string) $data['fase'],
        'è_crescente' => (bool) $data['è_crescente']
    ]);
}
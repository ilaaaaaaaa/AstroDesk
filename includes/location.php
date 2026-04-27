<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['lat']) && isset($data['lon'])) {
        $_SESSION['lat'] = $data['lat'];
        $_SESSION['lon'] = $data['lon'];
    }
    exit;
}
?>
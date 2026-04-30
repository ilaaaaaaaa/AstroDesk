<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    file_put_contents("debug.txt", file_get_contents("php://input"));
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['lat']) && isset($data['lng'])) {
        $_SESSION['lat'] = $data['lat'];
        $_SESSION['lng'] = $data['lng'];

        unset($_SESSION['lon']);
    }
    exit;
}
?>
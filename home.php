<?php
require_once __DIR__ . '/includes/db.php';

session_start();

// COORDINATE (di default Milano se non impostate dall'utente)
$lat = $_SESSION['lat'] ?? 45.4642;
$lng = $_SESSION['lng'] ?? 10.9916;

// Formatta le coordinate in numeri con 2 cifre decimali
$lat_display = number_format((float) $lat, 2);
$lng_display = number_format((float) $lng, 2);

// Ricavo i dati più recenti dei sensori da MongoDB
$collection = $db->sensori;
$latest = $collection->findOne(
    [],
    [
        'sort' => ['timestamp' => -1]
    ]
);
$temperatura = $latest['temperatura'] ?? null;
$umidita = $latest['umidita'] ?? null;
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroDesk</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/header.css">
</head>

<body>
    <?php require 'includes/header.php'; ?>

    <div class="hero">
        <div>
            <div class="hero-tag">
                <?= $lat_display ?>° N · <?= $lng_display ?>° E
            </div>
            <h1 class="hero-title">
                La tua stanza<br><em>sotto le stelle</em>
            </h1>
            <p class="hero-sub">
                Dati astronomici e condizioni ambientali<br>
                rilevati in tempo reale.
            </p>
        </div>
        <div class="page-icon">
            <img src="assets/images/bed.png" alt="Stanza" width="175" height="175">
        </div>
    </div>

    <!-- CARD SENSORI -->
    <div class="cards-wrap">
        <div class="cards">
            <div class="card">
                <div class="card-header">
                    <div class="card-tag">Temperatura</div>
                </div>
                <div class="card-label">Temperatura</div>
                <div class="card-value">
                    <?= $temperatura !== null ? $temperatura . "°C" : "—" ?>
                </div>
                <div class="card-divider"></div>
                <div class="card-sub">°C · BMP280</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-tag">Umidità</div>
                </div>
                <div class="card-label">Umidità relativa</div>
                <div class="card-value">
                    <?= $umidita !== null ? $umidita . "%" : "—" ?>
                </div>
                <div class="card-divider"></div>
                <div class="card-sub">% · SHT30</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-tag">Illuminazione dell'ambiente</div>
                </div>
                <div class="card-label">Illuminazione</div>
                <div class="card-value">—</div>
                <div class="card-divider"></div>
                <div class="card-sub">lux · BH1750</div>
            </div>
        </div>
    </div>

    <?php require 'includes/footer.php'; ?>
</body>
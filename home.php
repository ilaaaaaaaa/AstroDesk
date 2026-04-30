<?php
session_start();

// COORDINATE (di default Milano se non impostate dall'utente)
$lat = $_SESSION['lat'] ?? 45.4642;
$lng = $_SESSION['lng'] ?? 10.9916;

$lat_display = number_format((float) $lat, 2);
$lng_display = number_format((float) $lng, 2);
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

    <!--<pre><?php print_r($_SESSION); ?></pre>-->
    <!-- HERO -->
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
        <div class="moon-widget">
            <div class="moon-fill"></div>
            <div class="moon-label">
                <span class="moon-pct">—</span>
                illuminata
            </div>
        </div>
    </div>

    <!-- CARD SENSORI -->
    <div class="cards-wrap">
        <div class="cards">

            <div class="card">
                <div class="card-header">
                    <div class="card-tag">Pressione</div>
                </div>
                <div class="card-label">Pressione atmosferica</div>
                <div class="card-value">—</div>
                <div class="card-divider"></div>
                <div class="card-sub">hPa · BMP280</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-tag">Temperatura</div>
                </div>
                <div class="card-label">Temperatura</div>
                <div class="card-value">—</div>
                <div class="card-divider"></div>
                <div class="card-sub">°C · BMP280</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-tag">Umidità</div>
                </div>
                <div class="card-label">Umidità relativa</div>
                <div class="card-value">—</div>
                <div class="card-divider"></div>
                <div class="card-sub">% · SHT30</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-tag">—</div>
                </div>
                <div class="card-label">—</div>
                <div class="card-value">—</div>
                <div class="card-divider"></div>
                <div class="card-sub">—</div>
            </div>

        </div>
    </div>
    <script>
        const lat = Number(<?= json_encode($lat) ?>);
        const lng = Number(<?= json_encode($lng) ?>);
    </script>

    <?php require 'includes/footer.php'; ?>
</body>
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

    <!-- HERO -->
    <div class="hero">
        <div>
            <div class="hero-tag">Milano · 45.46° N · 9.19° E</div>
            <h1 class="hero-title">Il cielo<br>di <em>stanotte</em></h1>
            <p class="hero-sub">
                Dati ambientali in tempo reale<br>
                rilevati dall'ESP8266.
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
                    <div class="card-tag">—</div>
                </div>
                <div class="card-label">—</div>
                <div class="card-value">—</div>
                <div class="card-divider"></div>
                <div class="card-sub">—</div>
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

    <?php require 'includes/footer.php'; ?>
</body>
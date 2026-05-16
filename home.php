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

// Dati per il grafico: ultimi 60 documenti (≈ 1 ora)
$cursor = $collection->find(
    [],
    [
        'sort'  => ['timestamp' => -1],
        'limit' => 60,
    ]
);
// Converte e gira il cursore MongoDB in un array PHP
$docs = array_reverse(iterator_to_array($cursor));      
 
$labels_orario = [];    // array per etichette dell'asse x (orario)
$valoriTemp = [];
$valoriHum = [];
 
foreach ($docs as $doc) {
    // Per convertire il timestamp MongoDB in un oggetto DateTime PHP
    $ts = $doc['timestamp']->toDateTime();
    $labels_orario[] = $ts->format('Y-m-d\TH:i:s');
    $valoriTemp[] = $doc['temperatura'];
    $valoriHum[] = $doc['umidita'];
}
 
$labelsJson = json_encode($labels_orario);
$temJson = json_encode($valoriTemp);
$humJson = json_encode($valoriHum);
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
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
                    <?= $temperatura != null ? $temperatura . "°C" : "—" ?>
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
                    <?= $umidita != null ? $umidita . "%" : "—" ?>
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

    <!-- GRAFICO SENSORI -->
    <?php if (count($labels_orario) > 0): ?>
    <div class="cards-wrap">
        <canvas id="grafico-sensori"></canvas>
    </div>
    <script>
    new Chart(document.getElementById('grafico-sensori'), {
        type: 'line',
        data: {
            labels: <?= $labelsJson ?>,
            datasets: [     // Un dataset = una linea del grafico
                {
                    label: 'Temperatura (°C)',
                    data: <?= $temJson ?>,
                    borderColor: '#ff6b6b',
                    backgroundColor: 'rgba(255,107,107,0.08)',
                    tension: 0.3,       // Curvatura della linea
                    pointRadius: 2,     // Raggio dei punti dati
                    yAxisID: 'yTemp',   // Asse sinistro
                },
                {
                    label: 'Umidità (%)',
                    data: <?= $humJson ?>,
                    borderColor: '#4ecdc4',
                    backgroundColor: 'rgba(78,205,196,0.08)',
                    tension: 0.3,
                    pointRadius: 2,
                    yAxisID: 'yHum',
                }
            ]
        },
        options: {
            responsive: true,       // Adatta il grafico alla dimensione della finestra del browser
            plugins: {
                legend: { position: 'top' }     // Legenda in alto
            },
            scales: {
                x: {
                        type: 'time',       // Valori temporali sull'asse X
                        time: {
                            unit: 'minute',
                            displayFormats: { minute: 'HH:mm' }
                        },
                        ticks: { maxTicksLimit: 10 }        // Massimo 10 etichette sull'asse X
                    },
                yTemp: {
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: '°C' }
                },
                yHum: {
                    type: 'linear',
                    position: 'right',
                    title: { display: true, text: '%' },
                }
            }
        }
    });
    </script>
    <?php endif; ?>

    <?php require 'includes/footer.php'; ?>
</body>
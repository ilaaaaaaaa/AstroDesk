<?php
require_once 'includes/db.php';

$nasa_api_key = $_ENV['NASA_API_KEY'];

$collezione = $db->foto;

$oggi = date('Y-m-d');

// CONTROLLO SE I DATI DI OGGI ESISTONO GIÀ IN MONGODB 
$dati_db = $collezione->findOne(['date' => $oggi]);

if ($dati_db) {
    $apod = $dati_db;
} else {
    // Chiamata API NASA
    $url      = "https://api.nasa.gov/planetary/apod?api_key=$nasa_api_key&date=$oggi";
    $risposta = file_get_contents($url);
    $apod     = json_decode($risposta, true);

    $collezione->insertOne($apod);
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroDesk</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/foto_del_giorno.css">
</head>

<body>

    <?php require 'includes/header.php'; ?>

    <!-- HEADER PAGINA -->
    <div class="page-header">
        <div class="page-tag">// Foto del giorno</div>
        <h1 class="page-title">Foto del <em>Giorno</em></h1>
        <p class="page-sub">Immagine astronomica del giorno dalla NASA.</p>
    </div>

    <div class="page-content">

        <?php if (!$apod): ?>
        <!-- ERRORE: API non raggiungibile e nessun dato in db -->
        <p class="apod-errore">Impossibile recuperare la foto del giorno. Riprova più tardi.</p>

        <?php else: ?>

        <?php if ($apod['media_type'] === 'video'): ?>
        <!-- VIDEO (solitamente YouTube) -->
        <div class="apod-video">
            <iframe
                src="<?= htmlspecialchars($apod['url']) ?>"
                title="<?= htmlspecialchars($apod['title']) ?>"
                allowfullscreen>
            </iframe>
        </div>

        <?php else: ?>
        <!-- IMMAGINE -->
        <div class="apod-image">
            <img
                src="<?= htmlspecialchars($apod['hdurl'] ?? $apod['url']) ?>"
                alt="<?= htmlspecialchars($apod['title']) ?>">
        </div>

        <?php endif; ?>

        <!-- TITOLO E SPIEGAZIONE -->
        <div class="apod-body">
            <div class="apod-date"><?= date('d/m/Y', strtotime($apod['date'])) ?></div>
            <h2 class="apod-title"><?= htmlspecialchars($apod['title']) ?></h2>
            <p class="apod-text"><?= htmlspecialchars($apod['explanation']) ?></p>
        </div>

        <?php endif; ?>

    </div>

    <?php require 'includes/footer.php'; ?>

</body>
</html>
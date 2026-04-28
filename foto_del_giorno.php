<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroDesk</title>

    <link rel="stylesheet" href="assets/css/foto_del_giorno.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/header.css">
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

        <!-- IMMAGINE -->
        <div class="apod-image">
            <!-- src verrà impostato da PHP con il dato da MongoDB -->
            <img src="" alt="Foto astronomica del giorno">
        </div>

        <!-- TITOLO E SPIEGAZIONE -->
        <div class="apod-body">
            <div class="apod-date">—</div>
            <h2 class="apod-title">—</h2>
            <p class="apod-text">—</p>
        </div>

    </div>

    <?php require 'includes/footer.php'; ?>
</body>
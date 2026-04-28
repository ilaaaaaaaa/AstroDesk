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

    <!-- HEADER PAGINA -->
    <div class="page-header">
        <div class="page-tag">// Notte</div>
        <h1 class="page-title">La <em>Notte</em></h1>
        <p class="page-sub">Pianeti visibili, ore di buio e condizioni di osservazione.</p>
    </div>

    <div class="page-content">

        <!-- DATI PRINCIPALI -->
        <div class="data-grid">
            <div class="data-item">
                <div class="data-label">Ore di buio</div>
                <div class="data-value">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Momento migliore</div>
                <div class="data-value">—</div>
                <div class="data-sub">per osservare</div>
            </div>
        </div>

        <!-- PIANETI VISIBILI -->
        <div class="section-title">// <span>Pianeti</span> visibili stanotte</div>
        <div class="planets">
            <!-- Verrà generato da un loop PHP con astronomy-bundle-php -->
        </div>

    </div>

    <?php require 'includes/footer.php'; ?>
</body>
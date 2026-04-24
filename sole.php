<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroDesk</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php require 'includes/header.php'; ?>

    <!-- HEADER PAGINA -->
    <div class="page-header">
        <div class="page-tag">// Sole</div>
        <h1 class="page-title">Il <em>Sole</em></h1>
        <p class="page-sub">Alba, tramonto e dati solari aggiornati.</p>
    </div>

    <div class="page-content">

        <!-- DATI PRINCIPALI -->
        <div class="data-grid">
            <div class="data-item">
                <div class="data-label">Alba</div>
                <div class="data-value">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Tramonto</div>
                <div class="data-value">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Durata del giorno</div>
                <div class="data-value">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Mezzogiorno solare</div>
                <div class="data-value">—</div>
            </div>
        </div>

        <!-- CREPUSCOLI -->
        <div class="section-title">// <span>Crepuscoli</span></div>
        <div class="data-grid">
            <div class="data-item">
                <div class="data-label">Crepuscolo civile</div>
                <div class="data-value">—</div>
                <div class="data-sub">6° sotto l'orizzonte</div>
            </div>
            <div class="data-item">
                <div class="data-label">Crepuscolo nautico</div>
                <div class="data-value">—</div>
                <div class="data-sub">12° sotto l'orizzonte</div>
            </div>
            <div class="data-item">
                <div class="data-label">Crepuscolo astronomico</div>
                <div class="data-value">—</div>
                <div class="data-sub">18° sotto l'orizzonte</div>
            </div>
        </div>

    </div>

    <?php require 'includes/footer.php'; ?>
</body>
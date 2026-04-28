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
        <div class="page-tag">// Luna</div>
        <h1 class="page-title">La <em>Luna</em></h1>
        <p class="page-sub">Fase lunare, illuminazione e dati orbitali aggiornati.</p>
    </div>

    <div class="page-content">

        <!-- DATI PRINCIPALI -->
        <div class="data-grid">
            <div class="data-item">
                <div class="data-label">Fase</div>
                <div class="data-value">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Illuminazione</div>
                <div class="data-value">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Alba lunare</div>
                <div class="data-value">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Tramonto lunare</div>
                <div class="data-value">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Prossima luna piena</div>
                <div class="data-value">—</div>
            </div>
        </div>

        <!-- STORICO 7 GIORNI -->
        <div class="section-title">// <span>Storico</span> · ultimi 7 giorni</div>
        <div class="storico-grid">
            <!-- Verrà generato da un loop PHP su dati MongoDB -->
        </div>

    </div>

    <?php require 'includes/footer.php'; ?>
</body>
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
        <div class="page-tag">// Calendario</div>
        <h1 class="page-title">Calendario <em>Astronomico</em></h1>
        <p class="page-sub">Equinozi, solstizi ed eventi astronomici del mese.</p>
    </div>

    <div class="page-content">

        <!-- EVENTI DEL MESE -->
        <div class="section-title">// <span>Eventi</span> del mese</div>
        <div class="data-grid">
            <!-- Verrà generato da un loop PHP con astronomy-bundle-php -->
        </div>

        <!-- EQUINOZI E SOLSTIZI -->
        <div class="section-title">// <span>Equinozi</span> e Solstizi</div>
        <div class="data-grid">
            <div class="data-item">
                <div class="data-label">Equinozio di primavera</div>
                <div class="data-value">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Solstizio d'estate</div>
                <div class="data-value">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Equinozio d'autunno</div>
                <div class="data-value">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Solstizio d'inverno</div>
                <div class="data-value">—</div>
            </div>
        </div>

    </div>
    <?php require 'includes/footer.php'; ?>
</body>
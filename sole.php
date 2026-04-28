<?php
session_start();
$nebulaSole = true;

// COORDINATE 
// default Milano
$lat = $_SESSION['lat'] ?? 45.4642;
$lng = $_SESSION['lng'] ?? 10.9916;

// CHIAMATA API sunrise-sunset.org 
$url = "https://api.sunrise-sunset.org/json?lat=$lat&lng=$lng&date=today&formatted=0";
$risposta = file_get_contents($url);
$dati = json_decode($risposta, true);

// CONVERSIONE DA UTC A ORA ITALIANA 
function convertiOra($orario_utc)
{
    $data = new DateTime($orario_utc, new DateTimeZone('UTC'));
    $data->setTimezone(new DateTimeZone('Europe/Rome'));
    return $data->format('H:i');
}

// CALCOLO DURATA DEL GIORNO
function calcolaDurata($alba_utc, $tramonto_utc)
{
    $alba = new DateTime($alba_utc, new DateTimeZone('UTC'));
    $tramonto = new DateTime($tramonto_utc, new DateTimeZone('UTC'));
    $diff = $tramonto->getTimestamp() - $alba->getTimestamp();
    $ore = intdiv($diff, 3600);
    $minuti = intdiv($diff % 3600, 60);
    return $ore . 'h ' . str_pad($minuti, 2, '0', STR_PAD_LEFT) . 'm';
}

// LETTURA DEI VALORI DAL JSON 
$r = $dati['results'];

$alba = convertiOra($r['sunrise']);
$tramonto = convertiOra($r['sunset']);
$mezzogiorno = convertiOra($r['solar_noon']);
$durata = calcolaDurata($r['sunrise'], $r['sunset']);

$crep_civ_m = convertiOra($r['civil_twilight_begin']);
$crep_civ_s = convertiOra($r['civil_twilight_end']);
$crep_naut_m = convertiOra($r['nautical_twilight_begin']);
$crep_naut_s = convertiOra($r['nautical_twilight_end']);
$crep_astro_m = convertiOra($r['astronomical_twilight_begin']);
$crep_astro_s = convertiOra($r['astronomical_twilight_end']);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroDesk</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/sole.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/header.css">
</head>

<body id="page-sole">
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
                <div class="data-value"><?= $alba ?></div>
            </div>
            <div class="data-item">
                <div class="data-label">Tramonto</div>
                <div class="data-value"><?= $tramonto ?></div>
            </div>
            <div class="data-item">
                <div class="data-label">Durata del giorno</div>
                <div class="data-value"><?= $durata ?></div>
            </div>
            <div class="data-item">
                <div class="data-label">Mezzogiorno solare</div>
                <div class="data-value"><?= $mezzogiorno ?></div>
            </div>
        </div>

        <!-- CREPUSCOLI -->
        <div class="section-title">// <span>Crepuscoli</span></div>
        <div class="data-grid">
            <div class="data-item">
                <div class="data-label">Crepuscolo civile</div>
                <div class="data-value"><?= $crep_civ_m ?> — <?= $crep_civ_s ?></div>
                <div class="data-sub">6° sotto l'orizzonte</div>
            </div>
            <div class="data-item">
                <div class="data-label">Crepuscolo nautico</div>
                <div class="data-value"><?= $crep_naut_m ?> — <?= $crep_naut_s ?></div>
                <div class="data-sub">12° sotto l'orizzonte</div>
            </div>
            <div class="data-item">
                <div class="data-label">Crepuscolo astronomico</div>
                <div class="data-value"><?= $crep_astro_m ?> — <?= $crep_astro_s ?></div>
                <div class="data-sub">18° sotto l'orizzonte</div>
            </div>
        </div>

        <!-- STORICO 8 GIORNI (generato da JavaScript con SunCalc, escluso oggi) -->
        <div class="section-title">// <span>Storico</span></div>
        <div class="storico-grid" id="storico"></div>

    </div>

    <?php require 'includes/footer.php'; ?>

    <!-- SunCalc: calcola alba e tramonto per date passate, senza API -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/suncalc/1.9.0/suncalc.min.js"></script>

    <script>
        // Coordinate passate da PHP a JavaScript
        var lat = <?= $lat ?>;
        var lng = <?= $lng ?>;

        // Formatta un oggetto Date come "HH:MM"
        function formatOra(data) {
            var ore = String(data.getHours()).padStart(2, '0');
            var minuti = String(data.getMinutes()).padStart(2, '0');
            return ore + ':' + minuti;
        }

        // Calcolo della durata in ore e minuti tra due oggetti Date
        function formatDurata(alba, tramonto) {
            var diff = tramonto - alba;          // differenza in millisecondi
            var minTot = Math.round(diff / 60000); // minuti totali (arrotondati)
            var ore = Math.floor(minTot / 60);  // ore intere (NON arrotondate)
            var minuti = minTot % 60;              // minuti rimanenti
            return ore + 'h ' + String(minuti).padStart(2, '0') + 'm';
        }

        // Nomi dei giorni della settimana in italiano
        var nomiGiorni = ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];

        var oggi = new Date();

        // Storico degli ultimi 8 giorni, escluso oggi (da 8 giorni fa fino a ieri)
        var contenitore = document.getElementById('storico');

        for (var i = 8; i >= 1; i--) {

            // Calcolo della data del giorno (oggi - i giorni)
            var giorno = new Date(oggi);
            giorno.setDate(oggi.getDate() - i);

            // SunCalc calcola alba e tramonto per quella data e coordinate
            var orari = SunCalc.getTimes(giorno, lat, lng);
            var alba = orari.sunrise;
            var tram = orari.sunset;

            // Formatta l'etichetta del giorno (es. "Lun 21/4")
            var nomeDow = nomiGiorni[giorno.getDay()];
            var etichetta = nomeDow + ' ' + giorno.getDate() + '/' + (giorno.getMonth() + 1);

            // Creazione del blocco HTML per questo giorno
            var blocco = document.createElement('div');
            blocco.className = 'data-item';

            blocco.innerHTML =
                '<div class="data-label">' + etichetta + '</div>' +
                '<div class="data-value">' + formatOra(alba) + ' — ' + formatOra(tram) + '</div>' +
                '<div class="data-sub">' + formatDurata(alba, tram) + '</div>';

            contenitore.appendChild(blocco);
        }
    </script>
</body>

</html>
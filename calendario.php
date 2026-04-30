<?php
session_start();

$lat = $_SESSION['lat'] ?? 45.4642;
$lng = $_SESSION['lng'] ?? 10.9916;
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroDesk — Calendario</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <script src="https://cdn.jsdelivr.net/npm/astronomy-engine@2.1.19/astronomy.browser.min.js"></script>
</head>

<body>
    <?php require 'includes/header.php'; ?>

    <div class="page-header">
        <div class="page-tag">// Calendario</div>
        <h1 class="page-title">Calendario <em>Astronomico</em></h1>
        <p class="page-sub">Equinozi, solstizi ed eclissi dell'anno corrente.</p>
    </div>

    <div class="page-content">

        <!-- EQUINOZI E SOLSTIZI -->
        <div class="section-title">// <span>Equinozi</span> e Solstizi</div>
        <div class="data-grid" id="equinozi"></div>

        <!-- ECLISSI -->
        <div class="section-title">// <span>Eclissi</span> · prossimi 12 mesi</div>
        <div class="data-grid" id="eclissi"></div>

    </div>

    <script>
        const now = Astronomy.MakeTime(new Date());
        const anno = new Date().getFullYear();

        // Formatta data in italiano
        function formatData(astroTime) {
            if (!astroTime) return "—";
            return astroTime.date.toLocaleDateString('it-IT', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        // Formatta ora in HH:MM
        function formatOra(astroTime) {
            if (!astroTime) return "—";
            return astroTime.date.toLocaleTimeString('it-IT', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Crea una data-item generica
        function creaItem(label, valore, sub) {
            const item = document.createElement('div');
            item.className = 'data-item';
            item.innerHTML = `
                <div class="data-label">${label}</div>
                <div class="data-value">${valore}</div>
                ${sub ? `<div class="data-sub">${sub}</div>` : ''}
            `;
            return item;
        }

        // ── EQUINOZI E SOLSTIZI ──
        const eventi = [
            { label: 'Equinozio di primavera', tipo: 0 },
            { label: 'Solstizio d\'estate', tipo: 1 },
            { label: 'Equinozio d\'autunno', tipo: 2 },
            { label: 'Solstizio d\'inverno', tipo: 3 },
        ];

        const gridEquinozi = document.getElementById('equinozi');

        eventi.forEach(e => {
            const risultato = Astronomy.Seasons(anno);
            let astroTime;
            if (e.tipo === 0) astroTime = risultato.mar_equinox;
            if (e.tipo === 1) astroTime = risultato.jun_solstice;
            if (e.tipo === 2) astroTime = risultato.sep_equinox;
            if (e.tipo === 3) astroTime = risultato.dec_solstice;

            gridEquinozi.appendChild(
                creaItem(e.label, formatData(astroTime), formatOra(astroTime))
            );
        });

        // ── ECLISSI ──
        // ── ECLISSI ──
        const gridEclissi = document.getElementById('eclissi');
        const eclissi = [];

        // Raccoglie 3 eclissi solari
        let cercaSolare = now;
        for (let i = 0; i < 3; i++) {
            const eclisse = Astronomy.SearchGlobalSolarEclipse(cercaSolare);
            if (!eclisse) break;
            eclissi.push({
                label: 'Eclissi solare · ' + eclisse.kind,
                data: eclisse.peak.date,
                astroTime: eclisse.peak
            });
            cercaSolare = Astronomy.MakeTime(new Date(eclisse.peak.date.getTime() + 86400000));
        }

        // Raccoglie 3 eclissi lunari
        let cercaLunare = now;
        for (let i = 0; i < 3; i++) {
            const eclisse = Astronomy.SearchLunarEclipse(cercaLunare);
            if (!eclisse) break;
            eclissi.push({
                label: 'Eclissi lunare · ' + eclisse.kind,
                data: eclisse.peak.date,
                astroTime: eclisse.peak
            });
            cercaLunare = Astronomy.MakeTime(new Date(eclisse.peak.date.getTime() + 86400000));
        }

        // Ordina per data crescente
        eclissi.sort((a, b) => a.data - b.data);

        // Inserisce nella griglia
        eclissi.forEach(e => {
            gridEclissi.appendChild(
                creaItem(e.label, formatData(e.astroTime), formatOra(e.astroTime))
            );
        });
    </script>

    <?php require 'includes/footer.php'; ?>
</body>

</html>
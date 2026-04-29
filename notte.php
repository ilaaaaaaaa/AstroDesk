<?php
session_start();

// COORDINATE (di default Milano se non impostate dall'utente)
$lat = $_SESSION['lat'] ?? 45.4642;
$lng = $_SESSION['lng'] ?? 10.9916;
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
    <link rel="stylesheet" href="assets/css/notte.css">
    <link rel="stylesheet" href="assets/css/luna.css">

    <!-- Libreria Astronomy Engine -->
    <script src="https://cdn.jsdelivr.net/npm/astronomy-engine@2.1.19/astronomy.browser.min.js"></script>
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
                <div class="data-value" id="ore-buio">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Momento migliore</div>
                <div class="data-value" id="momento-migliore">—</div>
                <div class="data-sub">per osservare</div>
            </div>
        </div>

        <!-- PIANETI VISIBILI -->
        <div class="section-title">// <span>Pianeti</span> visibili stanotte</div>
        <div class="legenda">
            <span class="legenda-item"><span class="legenda-dot"
                    style="background: rgba(74, 222, 128, 0.3); border: 1px solid #4ade80;"></span>visibile</span>
            <span class="legenda-item"><span class="legenda-dot"
                    style="background: rgba(239, 68, 68, 0.3); border: 1px solid #ef4444;"></span>sotto
                l'orizzonte</span>
        </div>
        <div class="pianeti-grid" id="pianeti"></div>

    </div>
    <script>
        const lat = Number(<?= json_encode($lat) ?>);
        const lng = Number(<?= json_encode($lng) ?>);
        const observer = new Astronomy.Observer(lat, lng, 0);
        const now = Astronomy.MakeTime(new Date());

        // Lista pianeti da controllare
        const pianeti = [
            { nome: 'Mercurio', body: Astronomy.Body.Mercury },
            { nome: 'Venere', body: Astronomy.Body.Venus },
            { nome: 'Marte', body: Astronomy.Body.Mars },
            { nome: 'Giove', body: Astronomy.Body.Jupiter },
            { nome: 'Saturno', body: Astronomy.Body.Saturn },
            { nome: 'Urano', body: Astronomy.Body.Uranus },
            { nome: 'Nettuno', body: Astronomy.Body.Neptune },
        ];

        // Calcola altitudine attuale di un pianeta
        function getAltitudine(body) {
            const eq = Astronomy.Equator(body, now, observer, true, true);
            const hor = Astronomy.Horizon(now, observer, eq.ra, eq.dec, 'normal');
            return hor.altitude;
        }

        // Formatta ora in HH:MM
        function formatOra(astroTime) {
            if (!astroTime) return "—";
            return astroTime.date.toLocaleTimeString('it-IT', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Calcola ore di buio (tramonto sole → alba sole)
        const sunSet = Astronomy.SearchRiseSet(Astronomy.Body.Sun, observer, -1, now, 1);
        const sunRise = Astronomy.SearchRiseSet(Astronomy.Body.Sun, observer, +1, now, 1);

        if (sunSet && sunRise) {
            const diffMs = sunRise.date - sunSet.date;
            const ore = Math.floor(diffMs / 3600000);
            const min = Math.floor((diffMs % 3600000) / 60000);
            document.getElementById('ore-buio').innerText = ore + 'h ' + String(min).padStart(2, '0') + 'm';

            // Momento migliore = mezzanotte astronomica (metà tra tramonto e alba)
            const metaMs = sunSet.date.getTime() + diffMs / 2;
            const meta = new Date(metaMs);
            document.getElementById('momento-migliore').innerText =
                meta.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
        }

        // Popola la lista pianeti
        const container = document.getElementById('pianeti');

        pianeti.forEach(p => {
            const alt = getAltitudine(p.body);
            const visibile = alt > 0;

            const item = document.createElement('div');
            item.className = 'storico-item';

            item.innerHTML = `
        <div class="storico-giorno">${p.nome}</div>
        <div class="planet-dot" style="background: ${visibile ? 'rgba(74, 222, 128, 0.3)' : 'rgba(239, 68, 68, 0.3)'}; border: 1px solid ${visibile ? '#4ade80' : '#ef4444'};"></div>
        <div class="storico-pct">${alt.toFixed(1)}°</div>
        <div class="storico-fase">${visibile ? 'visibile' : 'sotto orizzonte'}</div>
    `;

            container.appendChild(item);
        });
    </script>
    <?php require 'includes/footer.php'; ?>
</body>

</html>
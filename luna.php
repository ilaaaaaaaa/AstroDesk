<?php
session_start();
require_once 'includes/db.php';

// COORDINATE (di default Milano se non impostate dall'utente)
$lat = $_SESSION['lat'] ?? 45.4642;
$lng = $_SESSION['lng'] ?? 10.9916;

// Leggo lo storico degli ultimi 7 giorni da MongoDB
$collection = $db->moon_phases;
$storico = $collection->find(
    [],
    [
        'sort' => ['data' => -1],
        'limit' => 7
    ]
);

// Converto lo storico in un formato JSON-friendly per JavaScript
$storico_json = [];
foreach ($storico as $doc) {
    $storico_json[] = [
        'data' => $doc['data']->toDateTime()->setTimezone(new DateTimeZone('Europe/Rome'))->format('d/m'),
        'illuminazione' => $doc['illuminazione'],
        'fase' => $doc['fase'],
        'è_crescente' => $doc['è_crescente']
    ];
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroDesk — Luna</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/luna.css">

    <script src="assets/js/glossario.js" defer></script>
    <script src="assets/js/tooltip.js" defer></script>

    <!-- Libreria Astronomy Engine -->
    <script src="https://cdn.jsdelivr.net/npm/astronomy-engine@2.1.19/astronomy.browser.min.js"></script>
</head>

<body>
    <?php require 'includes/header.php'; ?>

    <div class="page-header">
        <div class="page-tag">// Luna</div>
        <h1 class="page-title">La <em>Luna</em></h1>
        <p class="page-sub">Fase lunare, illuminazione e dati orbitali aggiornati.</p>
    </div>

    <div class="page-content">

        <div class="data-grid">
            <div class="data-item">
                <div class="data-label">Fase</div>
                <div class="data-value" id="moon-phase">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Illuminazione</div>
                <div class="data-value" id="moon-illumination">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Alba lunare</div>
                <div class="data-value" id="moonrise">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Tramonto lunare</div>
                <div class="data-value" id="moonset">—</div>
            </div>
            <div class="data-item">
                <div class="data-label">Prossima luna piena</div>
                <div class="data-value" id="next-fullmoon">—</div>
            </div>
        </div>

        <div class="section-title">// <span>Storico</span> · ultimi 7 giorni</div>
        <div class="storico-grid" id="storico-grid"></div>

    </div>

    <script>
        // Ricavo la posizione dell'utente da PHP
        const lat = Number(<?= json_encode($lat) ?>);
        const lng = Number(<?= json_encode($lng) ?>);

        // L'observer è un oggetto che rappresenta la posizione dell'osservatore sulla Terra
        const observer = new Astronomy.Observer(lat, lng, 0);
        const now = Astronomy.MakeTime(new Date());

        // Calcola la fase lunare in gradi (0° = nuova, 180° = piena) 
        // perché la libreria non fornisce direttamente il nome della fase
        const moonDeg = Astronomy.MoonPhase(now);

        // Illuminazione reale della luna
        const illum = Astronomy.Illumination(Astronomy.Body.Moon, now).phase_fraction;

        // Alba e tramonto lunare con SearchRiseSet
        const rise = Astronomy.SearchRiseSet(Astronomy.Body.Moon, observer, +1, now, 1);
        const set = Astronomy.SearchRiseSet(Astronomy.Body.Moon, observer, -1, now, 1);

        // Prossima luna piena (180°) nei prossimi 40 giorni
        const fullMoon = Astronomy.SearchMoonPhase(180, now, 40);

        // Converte i gradi in nome della fase
        function getPhaseName(deg) {
            if (deg < 45) return "Luna nuova";
            if (deg < 90) return "Crescente";
            if (deg < 135) return "Primo quarto";
            if (deg < 180) return "Gibbosa crescente";
            if (deg < 225) return "Luna piena";
            if (deg < 270) return "Gibbosa calante";
            if (deg < 315) return "Ultimo quarto";
            return "Calante";
        }

        // Formatta un AstroTime in HH:MM italiano
        function formatOra(astroTime) {
            if (!astroTime) return "—";
            return astroTime.date.toLocaleTimeString('it-IT', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Popola i dati principali
        document.getElementById("moon-phase").innerText = getPhaseName(moonDeg);
        document.getElementById("moon-illumination").innerText = Math.round(illum * 100) + "%";
        document.getElementById("moonrise").innerText = formatOra(rise);
        document.getElementById("moonset").innerText = formatOra(set);
        document.getElementById("next-fullmoon").innerText = fullMoon
            ? fullMoon.date.toLocaleDateString('it-IT', { day: '2-digit', month: 'long', year: 'numeric' })
            : "—";

        // Salva i dati di oggi in MongoDB (solo se non già salvati)
        fetch('api/save_moon.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                illuminazione: Math.round(illum * 100),
                fase: getPhaseName(moonDeg),
                'è_crescente': moonDeg < 180
            })
        });

        // Popola lo storico 7 giorni da MongoDB
        const storico = <?= json_encode($storico_json) ?>;
        const grid = document.getElementById('storico-grid');

        storico.forEach(giorno => {
            const item = document.createElement('div');
            item.className = 'storico-item';
            item.innerHTML = `
                <div class="storico-giorno">${giorno.data}</div>
                <div class="storico-luna">
                    <div class="storico-luna-fill" style="width:${giorno.illuminazione / 2}px"></div>
                </div>
                <div class="storico-pct">${giorno.illuminazione}%</div>
                <div class="storico-fase">${giorno.fase}</div>
            `;
            grid.appendChild(item);
        });
    </script>

    <?php require 'includes/footer.php'; ?>
</body>

</html>
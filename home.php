<?php
$page_title = 'Home';
$current_page = 'home';
require_once 'includes/header.php';

// Dati di esempio — verranno sostituiti da query SQLite
// Esempio: $dati = $db->query("SELECT * FROM ...")->fetch();
$illuminazione = 68; // percentuale
$fase = 'Gibbosa crescente';
$eta_luna = '9.2 giorni';
$alba = '06:02';
$tramonto = '20:14';
$durata_giorno = '14h 12m';
$mezzogiorno = '13:08';
$n_pianeti = 3;
$n_costellazioni = 12;

// Larghezza fill luna (metà cerchio = 75px su 150px di diametro)
$moon_fill_width = round(($illuminazione / 100) * 75);
?>

<!-- HERO -->
<div class="hero">
    <div>
        <div class="hero-tag">Milano · 45.46° N · 9.19° E</div>
        <h1 class="hero-title">Il cielo<br>di <em>stanotte</em></h1>
        <p class="hero-sub">
            Dati astronomici in tempo reale<br>
            aggiornati ogni 30 minuti dall'ESP8266.
        </p>
    </div>
    <div class="moon-widget">
        <div class="moon-fill" style="width: <?php echo $moon_fill_width; ?>px;"></div>
        <div class="moon-label">
            <span class="moon-pct">
                <?php echo $illuminazione; ?>%
            </span>
            illuminata
        </div>
    </div>
</div>

<!-- CARD RIEPILOGO -->
<div class="cards-wrap">
    <div class="cards">

        <a href="/astrodesk/luna.php" class="card">
            <div class="card-header">
                <div class="card-tag">Luna</div>
                <div class="card-arrow">→</div>
            </div>
            <div class="card-label">Fase lunare</div>
            <div class="card-value">
                <?php echo htmlspecialchars(explode(' ', $fase)[0]); ?>
            </div>
            <div class="card-divider"></div>
            <div class="card-sub">
                <?php echo htmlspecialchars(strtolower($fase)); ?> ·
                <?php echo $eta_luna; ?>
            </div>
        </a>

        <a href="/astrotracker/sole.php" class="card">
            <div class="card-header">
                <div class="card-tag">Sole</div>
                <div class="card-arrow">→</div>
            </div>
            <div class="card-label">Tramonto</div>
            <div class="card-value">
                <?php echo $tramonto; ?>
            </div>
            <div class="card-divider"></div>
            <div class="card-sub">alba
                <?php echo $alba; ?> ·
                <?php echo $durata_giorno; ?> di luce
            </div>
        </a>

        <a href="/astrotracker/pianeti.php" class="card">
            <div class="card-header">
                <div class="card-tag">Pianeti</div>
                <div class="card-arrow">→</div>
            </div>
            <div class="card-label">Visibili stanotte</div>
            <div class="card-value">
                <?php echo $n_pianeti; ?>
            </div>
            <div class="card-divider"></div>
            <div class="card-sub">Venere · Marte · Giove</div>
        </a>

        <a href="/astrotracker/costellazioni.php" class="card">
            <div class="card-header">
                <div class="card-tag">Costellazioni</div>
                <div class="card-arrow">→</div>
            </div>
            <div class="card-label">Visibili</div>
            <div class="card-value">
                <?php echo $n_costellazioni; ?>
            </div>
            <div class="card-divider"></div>
            <div class="card-sub">Orione al culmine</div>
        </a>

    </div>
</div>

<!-- DUE COLONNE: PIANETI + SOLE -->
<div class="sections">

    <div class="section">
        <div class="section-title">// <span>Pianeti</span> visibili · ore 23:00</div>
        <div class="planets">
            <!-- Questi verranno generati da un loop PHP su dati SQLite -->
            <div class="planet-row">
                <div>
                    <div class="planet-name">Venere</div>
                    <div class="planet-const">Toro</div>
                </div>
                <div class="planet-alt">
                    <strong>42.3°</strong>
                    <span>altitudine max</span>
                </div>
            </div>
            <div class="planet-row">
                <div>
                    <div class="planet-name">Marte</div>
                    <div class="planet-const">Gemelli</div>
                </div>
                <div class="planet-alt">
                    <strong>28.7°</strong>
                    <span>altitudine max</span>
                </div>
            </div>
            <div class="planet-row">
                <div>
                    <div class="planet-name">Giove</div>
                    <div class="planet-const">Cancro</div>
                </div>
                <div class="planet-alt">
                    <strong>15.1°</strong>
                    <span>altitudine max</span>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">// <span>Sole</span> · oggi</div>
        <div class="sole-grid">
            <div class="sole-item">
                <div class="sole-label">Alba</div>
                <div class="sole-value">
                    <?php echo $alba; ?>
                </div>
                <div class="sole-sub">crepuscolo civile 05:34</div>
            </div>
            <div class="sole-item">
                <div class="sole-label">Tramonto</div>
                <div class="sole-value">
                    <?php echo $tramonto; ?>
                </div>
                <div class="sole-sub">crepuscolo civile 20:42</div>
            </div>
            <div class="sole-item">
                <div class="sole-label">Mezzogiorno solare</div>
                <div class="sole-value">
                    <?php echo $mezzogiorno; ?>
                </div>
                <div class="sole-sub">transito zenitale</div>
            </div>
            <div class="sole-item">
                <div class="sole-label">Durata giorno</div>
                <div class="sole-value">
                    <?php echo $durata_giorno; ?>
                </div>
                <div class="sole-sub">+2m rispetto a ieri</div>
            </div>
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
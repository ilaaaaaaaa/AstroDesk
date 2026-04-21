<?php
// $page_title viene impostata da ogni pagina prima di includere questo file
$page_title = isset($page_title) ? $page_title . ' — Astrodesk' : 'Astrodesk';
$current_page = isset($current_page) ? $current_page : 'home';
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo htmlspecialchars($page_title); ?>
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/astrodesk/assets/css/style.css">
</head>

<body>

    <nav>
        <a href="/astrodesk/home.php" class="logo">Astro<span>desk</span></a>

        <div class="nav-links">
            <a href="/astrodesk/home.php" class="<?php echo $current_page === 'home' ? 'active' : ''; ?>">Home</a>
            <a href="/astrodesk/luna.php" class="<?php echo $current_page === 'luna' ? 'active' : ''; ?>">Luna</a>
            <a href="/astrodesk/sole.php" class="<?php echo $current_page === 'sole' ? 'active' : ''; ?>">Sole</a>
            <a href="/astrodesk/pianeti.php"
                class="<?php echo $current_page === 'pianeti' ? 'active' : ''; ?>">Pianeti</a>
            <a href="/astrodesk/costellazioni.php"
                class="<?php echo $current_page === 'costellazioni' ? 'active' : ''; ?>">Costellazioni</a>
            <a href="/astrodesk/about.php" class="<?php echo $current_page === 'about' ? 'active' : ''; ?>">About</a>
        </div>

        <div class="nav-live" id="nav-live">
            <div class="live-dot"></div>
            <span id="live-label">LIVE</span>
        </div>
    </nav>

    <main>
<footer>
    <div class="footer-left">Astrotracker · Hu Caterina &amp; Meggiolaro Ilaria</div>
    <div class="footer-right">ESP8266 · MongoDB · PHP · XAMPP</div>
</footer>

<script>
    // Richiesta di accesso alla posizione dell'utente per personalizzare i contenuti
    if (!sessionStorage.getItem('locationAsked')) {
        navigator.geolocation.getCurrentPosition(
            pos => {
                fetch('/astrodesk/includes/location.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        lat: pos.coords.latitude,
                        lon: pos.coords.longitude
                    })
                });
                sessionStorage.setItem('locationAsked', 'true');
            }
        );
    }

    // --- ANIMAZIONE DELLO SFONDO IN BASE ALLA PAGINA ---
    const isSole = document.body.id === 'page-sole';

    if (!isSole) {
        // Starfield per tutte le altre pagine
        const sf = document.getElementById('starfield');
        for (let i = 0; i < 180; i++) {
            const s = document.createElement('div');
            s.className = 'star';
            const size = Math.random() < 0.7 ? 1 : Math.random() < 0.85 ? 1.5 : 2;
            s.style.cssText = `width:${size}px;height:${size}px;left:${Math.random() * 100}%;top:${Math.random() * 100}%;--d:${2 + Math.random() * 5}s;--delay:${Math.random() * 6}s;--op:${0.2 + Math.random() * 0.7};`;
            sf.appendChild(s);
        }
    } else {
        // Particelle per la pagina sole
        const sf = document.getElementById('starfield');
        for (let i = 0; i < 120; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.cssText = `left:${Math.random() * 100}%;top:${Math.random() * 100}%;--d:${4 + Math.random() * 8}s;--delay:${Math.random() * 8}s;--op:${0.1 + Math.random() * 0.4};`;
            sf.appendChild(p);
        }
    }
</script>
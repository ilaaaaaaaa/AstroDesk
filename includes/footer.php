<link rel="stylesheet" href="/astrodesk/assets/css/footer.css">
<footer>
    <div class="footer-left">Astrotracker · Hu Caterina &amp; Meggiolaro Ilaria</div>
    <div class="footer-right">ESP8266 · MongoDB · PHP · XAMPP</div>
</footer>

<script>
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
</script>
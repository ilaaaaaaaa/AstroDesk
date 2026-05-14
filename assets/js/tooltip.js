function applicaTooltip(el) {
    const termine = el.textContent.trim().toLowerCase();
    if (typeof glossario == 'undefined' || !glossario[termine]) 
      return;

    const tip = document.createElement('div');
    tip.className = 'tooltip-box';
    tip.textContent = glossario[termine];
    document.body.appendChild(tip);

    el.addEventListener('mouseenter', function () {
        // Prende la posizione dell’elemento sullo schermo
        const r = el.getBoundingClientRect();
        // Centra il tooltip orizzontalmente sull’elemento
        tip.style.left = (r.left + r.width / 2) + 'px';
        // Lo posiziona leggermente sopra l’elemento
        tip.style.top = (r.top - 10) + 'px';
        // Lo mostra 
        tip.style.opacity = '1';
    });
    el.addEventListener('mouseleave', function () {
        tip.style.opacity = '0';
    });
}

// Per le pagine con elementi statici (es. sole.php)
// Aspetta che venga caricata tutta la pagina 
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.data-label').forEach(applicaTooltip);
});
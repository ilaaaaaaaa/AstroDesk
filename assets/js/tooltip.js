/*
document.querySelectorAll('.data-label').forEach(el => {
  const termine = el.textContent.trim().toLowerCase();
  if (glossario[termine]) {

    // Crea un div tooltip separato nel body
    const tip = document.createElement('div');
    tip.className = 'tooltip-box';
    tip.textContent = glossario[termine];
    document.body.appendChild(tip);

    el.addEventListener('mouseenter', function () {
      // Ottiene posizione e dimensioni dell’elemento nel viewport
      const r = el.getBoundingClientRect();
      // Posiziona il tooltip centrato orizzontalmente
      tip.style.left = (r.left + r.width / 2) + 'px';
      // Posiziona il tooltip sopra l’elemento
      tip.style.top = (r.top - 10) + 'px';
      tip.style.opacity = '1';
    });

    el.addEventListener('mouseleave', function () {
      tip.style.opacity = '0';
    });
  }
});
*/

function applicaTooltip(el) {
    const termine = el.textContent.trim().toLowerCase();
    if (typeof glossario == 'undefined' || !glossario[termine]) 
      return;

    const tip = document.createElement('div');
    tip.className = 'tooltip-box';
    tip.textContent = glossario[termine];
    document.body.appendChild(tip);

    el.addEventListener('mouseenter', function () {
        const r = el.getBoundingClientRect();
        tip.style.left = (r.left + r.width / 2) + 'px';
        tip.style.top = (r.top - 10) + 'px';
        tip.style.opacity = '1';
    });
    el.addEventListener('mouseleave', function () {
        tip.style.opacity = '0';
    });
}

// Per le pagine con elementi statici (es. sole.php)
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.data-label').forEach(applicaTooltip);
});
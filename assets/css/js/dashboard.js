// Aggiorna il badge LIVE con il tempo dall'ultimo aggiornamento
// In futuro qui andrà il fetch periodico verso api/data.php

document.addEventListener("DOMContentLoaded", function () {
  const label = document.getElementById("live-label");
  if (!label) return;

  // Legge il data-attribute impostato dal PHP (timestamp ultimo aggiornamento)
  const nav = document.getElementById("nav-live");
  const lastUpdate = nav ? nav.dataset.lastUpdate : null;

  if (lastUpdate) {
    const diff = Math.floor(
      (Date.now() - new Date(lastUpdate).getTime()) / 60000,
    );
    if (diff < 1) label.textContent = "LIVE · ORA";
    else if (diff < 60) label.textContent = "LIVE · AGG. " + diff + " MIN FA";
    else label.textContent = "LIVE · AGG. " + Math.floor(diff / 60) + "H FA";
  } else {
    label.textContent = "LIVE";
  }
});

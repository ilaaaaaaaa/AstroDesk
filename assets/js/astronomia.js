// Converte i gradi della fase lunare in nome italiano
function getPhaseName(deg) {
  if (deg < 22.5 || deg >= 337.5) return "Luna nuova";
  if (deg < 67.5) return "Falce crescente";
  if (deg < 112.5) return "Primo quarto";
  if (deg < 157.5) return "Gibbosa crescente";
  if (deg < 202.5) return "Luna piena";
  if (deg < 247.5) return "Gibbosa calante";
  if (deg < 292.5) return "Ultimo quarto";
  return "Falce calante";
}

// Formatta un AstroTime (oggetto della libreria Astronomy Engine) in HH:MM
function formatOra(astroTime) {
  if (!astroTime) return "--:--";
  return astroTime.date.toLocaleTimeString("it-IT", {
    hour: "2-digit",
    minute: "2-digit",
  });
}

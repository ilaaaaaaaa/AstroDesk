// Invia i dati astronomici all'ESP8266 tramite il broker MQTT
function pushDisplay(ora, data, alba, tramonto, luna_fase, luna_illum) {
  fetch("api/push_display.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      ora: ora,
      data: data,
      alba: alba,
      tramonto: tramonto,
      luna_fase: luna_fase,
      luna_illum: luna_illum,
    }),
  });
}

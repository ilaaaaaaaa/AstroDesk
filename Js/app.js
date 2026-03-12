// Starfield
const sf = document.getElementById('starfield');
for (let i = 0; i < 180; i++) {
  const s = document.createElement('div');
  s.className = 'star';
  const size = Math.random() < 0.7 ? 1 : Math.random() < 0.85 ? 1.5 : 2;
  s.style.cssText = `
    width:${size}px; height:${size}px;
    left:${Math.random()*100}%;
    top:${Math.random()*100}%;
    --d:${2+Math.random()*5}s;
    --delay:${Math.random()*6}s;
    --op:${0.2+Math.random()*0.7};
  `;
  sf.appendChild(s);
}

// Clock
function updateClock() {
  const now = new Date();
  const days = ['Domenica','Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato'];
  const months = ['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'];
  document.getElementById('current-date').textContent =
    `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
  document.getElementById('current-time').textContent =
    `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}:${String(now.getSeconds()).padStart(2,'0')} UTC+1`;
}
updateClock(); setInterval(updateClock, 1000);

// Draw LED ring with 9/12 lit
const ledSvg = document.querySelector('.led-ring-svg');
const total = 12, lit = 9;
const cx = 40, cy = 40, r = 33;
for (let i = 0; i < total; i++) {
  const angle = (i / total) * Math.PI * 2 - Math.PI / 2;
  const x = cx + r * Math.cos(angle);
  const y = cy + r * Math.sin(angle);
  const isLit = i < lit;
  const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
  circle.setAttribute('cx', x.toFixed(1));
  circle.setAttribute('cy', y.toFixed(1));
  circle.setAttribute('r', '3.5');
  circle.setAttribute('fill', isLit ? '#e8c87a' : '#1f2235');
  if (isLit) {
    circle.setAttribute('filter', 'url(#softGlow)');
    circle.style.opacity = 0.5 + (i / lit) * 0.5;
  }
  ledSvg.appendChild(circle);
}

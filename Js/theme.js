(function () {
  const KEY   = 'app-theme';
  const MODES = ['system', 'light', 'dark'];
  const ICONS = { system: 'bi-circle-half', light: 'bi-sun-fill', dark: 'bi-moon-fill' };
  const TIPS  = { system: 'Modo sistema', light: 'Modo claro', dark: 'Modo oscuro' };

  function get()  { return localStorage.getItem(KEY) || 'system'; }

  function apply(mode) {
    if (mode === 'system') {
      document.documentElement.removeAttribute('data-theme');
    } else {
      document.documentElement.setAttribute('data-theme', mode);
    }
    var btn  = document.getElementById('theme-toggle');
    var icon = document.getElementById('theme-icon');
    if (!btn || !icon) return;
    icon.className = 'bi ' + ICONS[mode];
    btn.title      = TIPS[mode];
  }

  function cycle() {
    var next = MODES[(MODES.indexOf(get()) + 1) % MODES.length];
    localStorage.setItem(KEY, next);
    apply(next);
  }

  apply(get());

  document.addEventListener('DOMContentLoaded', function () {
    apply(get());
    var btn = document.getElementById('theme-toggle');
    if (btn) btn.addEventListener('click', cycle);
  });
})();

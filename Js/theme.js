(function () {
  var KEY   = 'app-theme';
  var MODES = ['system', 'light', 'dark'];
  var ICONS = { system: 'bi-circle-half', light: 'bi-sun-fill', dark: 'bi-moon-fill' };

  function get() { return localStorage.getItem(KEY) || 'system'; }

  function apply(mode) {
    if (mode === 'system') {
      document.documentElement.removeAttribute('data-theme');
    } else {
      document.documentElement.setAttribute('data-theme', mode);
    }
    var icon = document.getElementById('theme-icon');
    if (icon) icon.className = 'bi ' + ICONS[mode];
    var loginBtn = document.getElementById('theme-toggle-login');
    if (loginBtn) loginBtn.title = mode;
  }

  apply(get());

  document.addEventListener('DOMContentLoaded', function () {
    apply(get());
    var loginBtn = document.getElementById('theme-toggle-login');
    if (loginBtn) {
      loginBtn.addEventListener('click', function () {
        var next = MODES[(MODES.indexOf(get()) + 1) % MODES.length];
        localStorage.setItem(KEY, next);
        apply(next);
      });
    }
  });
})();

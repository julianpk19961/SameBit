<?php
// $page, $appName, $app_lang come from header.php (via setup.php)
$nav_items = [
  ['href' => './dashboard.php',  'icon' => 'bi-speedometer2',    'label' => __('home')              ?? 'Dashboard',      'id' => 'nav-dashboard'],
  ['href' => './calls.php',      'icon' => 'bi-telephone',       'label' => __('call_registry')      ?? 'Llamadas',       'id' => 'nav-calls'],
  ['href' => './medicines_l.php','icon' => 'bi-capsule',         'label' => __('medicines')           ?? 'Samecomed',      'id' => 'nav-medicines'],
  ['href' => './pacients.php',   'icon' => 'bi-people',          'label' => __('patients')            ?? 'Pacientes',      'id' => 'nav-patients'],
  ['href' => './asisttop.php',   'icon' => 'bi-clipboard2-pulse','label' => __('asist_top')          ?? 'Asist-TOP',      'id' => 'nav-asisttop'],
  ['href' => '#',                'icon' => 'bi-graph-up',        'label' => __('reports')             ?? 'Reportes',       'id' => 'nav-reports'],
  ['href' => './admin_users.php','icon' => 'bi-person-gear',     'label' => __('users_management')    ?? 'Usuarios',       'id' => 'nav-admin'],
];
?>
<nav class="app-navbar">
  <a href="./dashboard.php" class="nav-brand">
    <img src="../img/logo.png" alt="<?php echo htmlspecialchars($appName); ?>">
  </a>

  <div class="nav-center">
    <a href="./dashboard.php" class="nav-home-link<?php echo $page === 'dashboard.php' ? ' active' : ''; ?>">
      <i class="bi bi-speedometer2"></i> <?php echo __('home'); ?>
    </a>

    <div class="dropdown">
      <button class="nav-dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <i class="bi bi-grid"></i> <?php echo __('modules'); ?> <i class="bi bi-chevron-down nav-caret"></i>
      </button>
      <ul class="dropdown-menu nav-modules-menu">
        <?php foreach ($nav_items as $item): ?>
          <?php if ($item['id'] === 'nav-dashboard') continue; ?>
          <?php
            $active = ($page === basename($item['href'])) ? ' active' : '';
            $click  = ($item['id'] === 'nav-reports') ? ' onclick="openReportModal(event)"' : '';
          ?>
          <li>
            <a href="<?php echo $item['href']; ?>"
               id="<?php echo $item['id']; ?>"
               class="dropdown-item<?php echo $active; ?>"
               <?php echo $click; ?>>
              <i class="bi <?php echo $item['icon']; ?>"></i>
              <?php echo $item['label']; ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <div class="nav-right">
    <div class="dropdown">
      <button class="nav-user-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle"></i>
        <span class="nav-user-name"><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
        <i class="bi bi-chevron-down nav-caret"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end nav-user-menu">
        <li class="nav-user-header">
          <span class="nav-user-fullname"><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <button class="dropdown-item" id="theme-toggle-nav" onclick="cycleTheme()">
            <i class="bi bi-circle-half" id="theme-icon"></i>
            <?php echo __('system_mode'); ?>
          </button>
        </li>
        <li>
          <div class="nav-lang-row">
            <span class="nav-lang-label"><i class="bi bi-translate"></i> <?php echo __('language'); ?></span>
            <div class="nav-lang-btns">
              <button class="nav-lang-btn<?php echo $app_lang === 'en' ? ' active' : ''; ?>" onclick="setLanguage('en')">EN</button>
              <button class="nav-lang-btn<?php echo $app_lang === 'es' ? ' active' : ''; ?>" onclick="setLanguage('es')">ES</button>
            </div>
          </div>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <a href="../config/logout.php" class="dropdown-item nav-logout-item">
            <i class="bi bi-box-arrow-right"></i> <?php echo __('sign_out'); ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
function setLanguage(lang) {
  $.post('../config/set_language.php', { lang: lang }, function () {
    location.reload();
  });
}

function cycleTheme() {
  var theme = (function(){
    var KEY = 'app-theme';
    var MODES = ['system','light','dark'];
    var cur = localStorage.getItem(KEY) || 'system';
    var next = MODES[(MODES.indexOf(cur) + 1) % MODES.length];
    localStorage.setItem(KEY, next);
    return next;
  })();
  if (theme === 'system') {
    document.documentElement.removeAttribute('data-theme');
  } else {
    document.documentElement.setAttribute('data-theme', theme);
  }
  var ICONS = { system:'bi-circle-half', light:'bi-sun-fill', dark:'bi-moon-fill' };
  var icon = document.getElementById('theme-icon');
  if (icon) icon.className = 'bi ' + ICONS[theme];
}

function openReportModal(e) {
  e.preventDefault();
  var modal = document.getElementById('modal-report');
  if (modal) {
    var bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    if (typeof showReportCard === 'function') {
      showReportCard(new Date());
    }
  }
}
</script>

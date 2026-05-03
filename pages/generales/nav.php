<?php
// $page, $appName, $app_lang, $profileSlug, $conn vienen de setup.php (vía header.php)

// Módulos a los que el usuario tiene permiso 'ingresar'
$accessible_modules = [];
if (isset($_SESSION['id'])) {
    $nav_stmt = $conn->prepare("
        SELECT m.slug
        FROM profile_permissions pp
        INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
        INNER JOIN modules m ON mp.module_id = m.id
        INNER JOIN permissions p ON mp.permission_id = p.id
        INNER JOIN users u ON u.profile_id = pp.profile_id
        WHERE u.id = ?
          AND p.slug = 'ingresar'
          AND pp.can_access = 1
          AND m.active = 1
          AND u.active = 1
    ");
    $nav_stmt->bind_param('s', $_SESSION['id']);
    $nav_stmt->execute();
    $nav_result = $nav_stmt->get_result();
    while ($nav_row = $nav_result->fetch_assoc()) {
        $accessible_modules[] = $nav_row['slug'];
    }
    $nav_stmt->close();
}

$is_admin_nav = ($profileSlug === 'admin');

$nav_items = [
  ['href' => './dashboard.php',       'icon' => 'bi-speedometer2',     'label' => __('home')              ?? 'Dashboard',    'id' => 'nav-dashboard'],
  ['href' => './calls.php',           'icon' => 'bi-telephone',        'label' => __('call_registry')      ?? 'Llamadas',     'id' => 'nav-calls',      'module_slug' => 'llamadas_samebit'],
  ['href' => './medicines_l.php',     'icon' => 'bi-capsule',          'label' => __('medicines')           ?? 'Samecomed',    'id' => 'nav-medicines',  'module_slug' => 'medicina_samecomed'],
  ['href' => './pacients.php',        'icon' => 'bi-people',           'label' => __('patients')            ?? 'Pacientes',    'id' => 'nav-patients',   'module_slug' => 'pacientes'],
  ['href' => './asisttop.php',        'icon' => 'bi-clipboard2-pulse', 'label' => __('asist_top')          ?? 'Asist-TOP',    'id' => 'nav-asisttop',   'module_slug' => 'asist_top'],
  ['href' => '#',                     'icon' => 'bi-graph-up',         'label' => __('reports')             ?? 'Reportes',     'id' => 'nav-reports',    'module_slug' => 'reportes_dashboard'],
  ['href' => './admin_users.php',     'icon' => 'bi-person-gear',      'label' => __('users_management')    ?? 'Usuarios',     'id' => 'nav-admin',      'module_slug' => 'admin_usuarios', 'admin_only' => true],
  ['href' => './admin_profiles.php', 'icon' => 'bi-person-badge',  'label' => __('profiles') ?? 'Perfiles', 'id' => 'nav-profiles', 'admin_only' => true],
  ['href' => './admin_modules.php',  'icon' => 'bi-grid-3x3-gap', 'label' => __('modules')  ?? 'Módulos',  'id' => 'nav-modules',  'admin_only' => true],
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
            // Ocultar ítems exclusivos de admin
            if (!empty($item['admin_only']) && !$is_admin_nav) continue;

            // Ocultar módulos sin permiso 'ingresar' (solo para no-admin y si tiene module_slug)
            if (!$is_admin_nav && !empty($item['module_slug']) && !in_array($item['module_slug'], $accessible_modules)) continue;

            $active = ($page === basename($item['href'])) ? ' active' : '';
            $click  = ($item['id'] === 'nav-reports') ? ' onclick="openReportModal(event)"' : '';

            // Separador visual antes de los ítems de admin
            if (!empty($item['admin_only']) && $item['id'] === 'nav-admin'): ?>
              <li><hr class="dropdown-divider"></li>
              <li class="dropdown-header text-muted small px-3">Administración</li>
            <?php endif; ?>
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
          <br><small class="text-muted"><?php echo htmlspecialchars($profileSlug ?? ''); ?></small>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <button class="dropdown-item" id="theme-toggle-nav" onclick="cycleTheme()">
            <i class="bi bi-circle-half" id="theme-icon"></i>
            <span id="theme-label"><?php echo __('system_mode'); ?></span>
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
var THEME_LABELS = {
  system: '<?php echo addslashes(__('system_mode')); ?>',
  light:  '<?php echo addslashes(__('light_mode')); ?>',
  dark:   '<?php echo addslashes(__('dark_mode')); ?>'
};
var THEME_ICONS  = { system: 'bi-circle-half', light: 'bi-sun-fill', dark: 'bi-moon-fill' };

function _applyThemeUI(mode) {
  var icon  = document.getElementById('theme-icon');
  var label = document.getElementById('theme-label');
  if (icon)  icon.className   = 'bi ' + (THEME_ICONS[mode]  || THEME_ICONS.system);
  if (label) label.textContent = THEME_LABELS[mode] || THEME_LABELS.system;
}

document.addEventListener('DOMContentLoaded', function () {
  _applyThemeUI(localStorage.getItem('app-theme') || 'system');
});

function setLanguage(lang) {
  $.post('../config/set_language.php', { lang: lang }, function () {
    location.reload();
  });
}

function cycleTheme() {
  var KEY   = 'app-theme';
  var MODES = ['system', 'light', 'dark'];
  var cur   = localStorage.getItem(KEY) || 'system';
  var next  = MODES[(MODES.indexOf(cur) + 1) % MODES.length];
  localStorage.setItem(KEY, next);
  if (next === 'system') {
    document.documentElement.removeAttribute('data-theme');
  } else {
    document.documentElement.setAttribute('data-theme', next);
  }
  _applyThemeUI(next);
}

function openReportModal(e) {
  e.preventDefault();
  var modal = document.getElementById('modal-report');
  if (modal) {
    new bootstrap.Modal(modal).show();
    if (typeof showReportCard === 'function') showReportCard(new Date());
  }
}
</script>

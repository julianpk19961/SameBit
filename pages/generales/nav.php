<?php
// $page, $appName and $app_lang come from header.php (via setup.php)
?>
<header class="d-flex flex-wrap justify-content-between align-items-center py-3 mb-4 border-bottom px-4">
  <a href="/pages/dashboard.php" class="d-flex align-items-center text-dark text-decoration-none">
    <img src="../img/logo.png" height="40" class="logo">
    <span class="ms-2 fw-bold text-primary"><?php echo htmlspecialchars($appName); ?></span>
  </a>
  <nav>
    <ul class="nav nav-pills align-items-center gap-1">
      <?php if ($page !== 'dashboard.php'): ?>
      <li class="nav-item">
        <a href="./dashboard.php" class="nav-link"><i class="bi bi-house"></i> <?php echo __('home'); ?></a>
      </li>
      <?php endif; ?>
      <li class="nav-item">
        <span class="nav-link text-muted">
          <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['usuario']); ?>
        </span>
      </li>
      <li class="nav-item">
        <button id="theme-toggle" class="btn btn-sm btn-outline-secondary" title="<?php echo __('system_mode'); ?>">
          <i class="bi bi-circle-half" id="theme-icon"></i>
        </button>
      </li>
      <!-- Language switcher -->
      <li class="nav-item dropdown">
        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" id="lang-dropdown" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo __('language'); ?>">
          <i class="bi bi-translate"></i>
          <?php echo $app_lang === 'es' ? '🇨🇴' : '🇺🇸'; ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="lang-dropdown">
          <li>
            <button class="dropdown-item <?php echo $app_lang === 'en' ? 'active' : ''; ?>" onclick="setLanguage('en')">
              🇺🇸 <?php echo __('lang_en'); ?>
            </button>
          </li>
          <li>
            <button class="dropdown-item <?php echo $app_lang === 'es' ? 'active' : ''; ?>" onclick="setLanguage('es')">
              🇨🇴 <?php echo __('lang_es'); ?>
            </button>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="../config/logout.php" class="nav-link link-danger"><?php echo __('sign_out'); ?></a>
      </li>
    </ul>
  </nav>
</header>

<script>
function setLanguage(lang) {
  $.post('../config/set_language.php', { lang: lang }, function () {
    location.reload();
  });
}
</script>

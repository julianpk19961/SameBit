<?php
// $page y $appName vienen de header.php (via setup.php)
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
        <a href="./dashboard.php" class="nav-link"><i class="bi bi-house"></i> Inicio</a>
      </li>
      <?php endif; ?>
      <li class="nav-item">
        <span class="nav-link text-muted">
          <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['usuario']); ?>
        </span>
      </li>
      <li class="nav-item">
        <button id="theme-toggle" class="btn btn-sm btn-outline-secondary" title="Modo sistema">
          <i class="bi bi-circle-half" id="theme-icon"></i>
        </button>
      </li>
      <li class="nav-item">
        <a href="../config/logout.php" class="nav-link link-danger">Cerrar Sesión</a>
      </li>
    </ul>
  </nav>
</header>

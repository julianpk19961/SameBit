<?php
include './generales/header.php';
?>
<script src="../Js/Login.Js" defer></script>

<div class="login-wrapper">
  <button id="theme-toggle-login" aria-label="Toggle theme" title="Toggle theme (System/Light/Dark)">
    <i id="theme-icon" class="bi bi-circle-half"></i>
  </button>

  <div class="login-card">

    <div class="login-logo">
      <img src="../img/logo.png" alt="<?php echo htmlspecialchars($appName); ?>">
    </div>

    <h1 class="login-title"><?php echo __('welcome'); ?></h1>
    <p class="login-subtitle"><?php echo htmlspecialchars($appName); ?></p>

    <form id="login-form" method="POST" class="login-form" autocomplete="off">
      <div class="login-field">
        <div class="login-field-icon">
          <i class="bi bi-person"></i>
        </div>
        <input id="login__username" type="text" name="username"
               class="login-input" placeholder="<?php echo __('username'); ?>"
               autocomplete="username" required>
      </div>

      <div class="login-field">
        <div class="login-field-icon">
          <i class="bi bi-lock"></i>
        </div>
        <input id="login__password" type="password" name="password"
               class="login-input" placeholder="<?php echo __('password'); ?>"
               autocomplete="current-password" required>
      </div>

      <button class="login-btn" type="submit"><?php echo __('sign_in'); ?></button>
    </form>

    <div id="loader" class="login-loader hidden">
      <div class="login-spinner"></div>
    </div>

  </div>
</div>

</body>
</html>

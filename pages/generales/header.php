<?php
include '../config/setup.php';

$link       = $_SERVER['PHP_SELF'];
$link_array = explode('/', $link);
$page       = end($link_array);

// Generar token CSRF para la sesión actual
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($app_lang); ?>" id="html-root">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="<?php echo htmlspecialchars($csrf_token); ?>">
  <title><?php echo htmlspecialchars($title); ?></title>
  <script>(function(){var t=localStorage.getItem('app-theme');if(t&&t!=='system')document.documentElement.setAttribute('data-theme',t);})();</script>

  <link rel="icon" href="../img/logo.png" type="image/png">
  <link rel="stylesheet" href="../css/bootstrap-css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/datatables/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="../css/datatables/buttons.dataTables.min.css">
  <link rel="stylesheet" href="../css/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/font-awesome/font-awesome.min.css">
  <link rel="stylesheet" href="../css/select2/select2.min.css">
  <link rel="stylesheet" href="../Js/sweetalert2/sweetalert2.min.css">

  <link rel="stylesheet" href="../css/theme.css">
</head>
<body<?php if ($page === 'login.php') echo ' class="login-body"'; ?>>
  <!-- Variables globales: CSRF token, idioma y traducciones -->
  <script>
    window.CSRF_TOKEN = '<?php echo htmlspecialchars($csrf_token); ?>';
    window.APP_LANG   = '<?php echo $app_lang; ?>';
    window.LANG       = <?php echo json_encode($translations, JSON_UNESCAPED_UNICODE); ?>;
  </script>
  
  <script src="../Js/jquery/jquery-3.6.1.min.js"></script>
  <script src="../Js/jszip/jszip.min.js"></script>
  <script src="../Js/FileSaver/FileSaver.min.js"></script>
  <script src="../Js/sweetalert2/sweetalert2.min.js"></script>
  <script src="../Js/popper/popper.min.js"></script>
  <script src="../Js/bootstrap/bootstrap.min.js"></script>
  
  <!-- Script de seguridad para manejo de CSRF y autenticación -->
  <script src="../Js/security.js"></script>
  <script src="../Js/theme.js"></script>

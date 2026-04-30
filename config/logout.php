<?php
include 'setup.php';

// Limpiar variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redirigir a login
header("Location: " . $index . "pages/login.php");
exit();
?>


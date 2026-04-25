<?php
include 'setup.php';

// Destruir la sesión
session_destroy();

// Limpiar variables de sesión
$_SESSION = array();

// Redirigir a login
header("Location: " . $index . "pages/login.php");
exit();
?>


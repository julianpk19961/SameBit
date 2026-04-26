<?php
$host   = getenv('DB_HOST') ?: "localhost";
$port   = 3306;
$socket = "";
$user   = "usrconect";
$password = "toor";
$dbname = "bit_medical";

$conn = new mysqli($host, $user, $password, $dbname, $port, $socket);

if (!$conn) {
    die("No hay conexión: " . mysqli_connect_error());
}

// utf8mb4 soporta el rango Unicode completo (emojis, caracteres especiales, etc.)
// SET NAMES garantiza que la capa de transporte también use utf8mb4
if (!$conn->set_charset("utf8mb4")) {
    die("Error al configurar charset utf8mb4: " . $conn->error);
}
$conn->query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");

// Flags estándar para json_encode en todo el proyecto:
// - JSON_UNESCAPED_UNICODE  : "í" en vez de "í"
// - JSON_UNESCAPED_SLASHES  : "/" en vez de "\/"
define('JSON_OUT', JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

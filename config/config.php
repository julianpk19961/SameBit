<?php
// Datos sesion
$host="localhost";
$port=3306;
$socket="";
$user="usrconect";
$password="toor";
$dbname="samebit";

#Estabelercer conexión
$conn = new mysqli($host, $user, $password, $dbname, $port, $socket);

if (!$conn) {
  die("No hay conexión: ".mysqli_connect_error());
}

//cambiar el conjunto de caracteres a utf8 
if(!$conn->set_charset("utf8")){
  printf("Error cargando el conjunto de caracteres utf8: %\n", $conexion->error);
  exit();
}


?>

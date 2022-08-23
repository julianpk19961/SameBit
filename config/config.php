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

?>
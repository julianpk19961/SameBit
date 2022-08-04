<?php
#Conexión
include('config.php');

#Captura Variables
$Dni = isset($_POST["username"])?$_POST["username"]:'';



$Name = isset($_POST["password"])?$_POST["password"]:'';
$LastName = isset($_POST["password"])?$_POST["password"]:'';
$CellPhone = isset($_POST["password"])?$_POST["password"]:'';
$CommentDate = isset($_POST["username"])?$_POST["username"]:'';
$CommentHour = isset($_POST["password"])?$_POST["password"]:'';
$Comment = isset($_POST["password"])?$_POST["password"]:'';
$Acept = isset($_POST["password"])?$_POST["password"]:'';
$AppointmentDate = isset($_POST["username"])?$_POST["username"]:'';
$AppointmentHour = isset($_POST["password"])?$_POST["password"]:'';
$Eps = isset($_POST["password"])?$_POST["password"]:'';
$StatusEps = isset($_POST["password"])?$_POST["password"]:'';
$Ips = isset($_POST["username"])?$_POST["username"]:'';
$Range = isset($_POST["password"])?$_POST["password"]:'';
$Diagnostic = isset($_POST["password"])?$_POST["password"]:'';
$Calls = isset($_POST["password"])?$_POST["password"]:'';
$Remitter = isset($_POST["password"])?$_POST["password"]:'';
$StatusReg = isset($_POST["password"])?$_POST["password"]:''; 

$AccountCrea = isset($_POST["password"])?$_POST["password"]:'';
$AccountModify = isset($_POST["password"])?$_POST["password"]:'';
$HourCrea= isset($_POST["password"])?$_POST["password"]:'';
$HourModify= isset($_POST["password"])?$_POST["password"]:'';

?>
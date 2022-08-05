<?php
#Conexión
include('config.php');

#Captura Variables
$Dni = isset($_POST["username"])?$_POST["username"]:'';



$Dni = isset($_POST["Dni"])?$_POST["Dni"]:'';
$Name = isset($_POST["Name"])?$_POST["Name"]:'';
$LastName = isset($_POST["LastName"])?$POST["LastName"]:'';
$Phone = isset($_POST["Phone"])?$POST["Phone"]:'';
$CommentDate = isset($_POST["CommentDate"])?$_POST["CommentDate"]:'';
$CommentHour = isset($_POST["CommentHour"])?$_POST["CommentHour"]:'';
$Diagnosis = isset($_POST["Diagnosis"])?$_POST["Diagnosis"]:'';
$Accept = isset($_POST["Accept"])?$_POST["Accept"]:'';
$Eps = isset($_POST["Eps"])?$_POST["Eps"]:'';
$StatusEps = isset($_POST["StatusEps"])?$_POST["StatusEps"]:'';
$SentBy = isset($_POST["SentBy"])?$_POST["SentBy"]:'';
$Ips = isset($_POST["Ips"])?$_POST["Ips"]:'';
$AppointmentDate = isset($_POST["AppointmentDate"])?$_POST["AppointmentDate"]:'';
$CallsNumber = isset($_POST["CallsNumber"])?$_POST["CallsNumber"]:'';
$Comment = isset($_POST["Comment"])?$_POST["Comment"]:'';
?>
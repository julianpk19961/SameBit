<?php
// #Conexión
include('config.php');
session_start();

header('Content-Type: text/html; charset=UTF-8');
// Establecer zona horaria.
date_default_timezone_set('America/Bogota');

// Captura Variables.
$PK_UUID = isset($_POST["pk_uuid"]) ? $_POST["pk_uuid"] : '';
$FK_EPS = isset($_POST["Eps"]) ? $_POST["Eps"] : '';
$FK_Ips = isset($_POST["ips"]) ? $_POST["ips"] : '';
$FK_Range = isset($_POST["EpsClassification"]) ? $_POST["EpsClassification"] : '';
$FK_Diagnosis = isset($_POST["diagnosis"]) ? $_POST["diagnosis"] : '';
$dni = isset($_POST["dni"]) ? $_POST["dni"] : '';
$documenttype = isset($_POST["documenttype"]) ? $_POST["documenttype"] : '';
$name = isset($_POST["name"]) ? $_POST["name"] : '';
$lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : '';
$contactype = isset($_POST["contacttype"]) ? $_POST["contacttype"] : '';
$commentdate = isset($_POST["CommentDate"]) ? $_POST["CommentDate"] : '';
$commenttime = isset($_POST["CommentTime"]) ? $_POST["CommentTime"] : '';

$checkInDate = isset($_POST["checkInDate"]) ? $_POST["checkInDate"] : '';
$checkInTime = isset($_POST["checkInTime"]) ? $_POST["checkInTime"] : '';

$approved = isset($_POST["approved"]) ? $_POST["approved"] : '';
$appointmentdate = isset($_POST["AtentionDate"]) ? $_POST["AtentionDate"] : 'NULL';
$appointmenttime = isset($_POST["AtentionTime"]) ? $_POST["AtentionTime"] : '';
$comment = isset($_POST["ObservationIn"]) ? $_POST["ObservationIn"] : '';
$sentby = isset($_POST["SentBy"]) ? $_POST["SentBy"] : '';
$statuseps = isset($_POST["EpsStatus"]) ? $_POST["EpsStatus"] : '';
$callsnumber = isset($_POST["CallNumber"]) ? $_POST["CallNumber"] : '';

$exhibitNine = isset($_POST["exhibitNine"]) ? $_POST["exhibitNine"] : 0;
$exhibitTen = isset($_POST["exhibitTen"]) ? $_POST["exhibitTen"] : 0;
$sendTo = isset($_POST["sendTo"]) ? $_POST["sendTo"] : '';
$commentOut = isset($_POST["ObservationOut"]) ? $_POST["ObservationOut"] : '';

$username = $_SESSION['usuario'];


$days_diff = date_diff($checkInDate, $commentdate);
echo $days_diff;
return false;

// Crear-Actualizar registros.
if (empty($PK_UUID)) {
    // Crear el registro.
    $sql = "INSERT INTO Patients (KP_UUID,Dni,documentType,Name0,LastName0,Fk_Eps,FK_Range,`FK_Ips`,createdUser,updatedUser) 
                VALUES ( UUID(),'$dni','$documenttype','$name','$lastname','$FK_EPS','$FK_Range','$FK_Ips','$username','$username')";
} else {
    // Actualizar el registro.
    $sql = "UPDATE Patients SET Dni = '$dni' , documentType = '$documenttype' , Name0 = '$name', LastName0 = '$lastname', 
    Fk_Eps = '$FK_EPS', Fk_Ips = '$FK_Ips', FK_Range = '$FK_Range' , updatedUser = '$username' 
    WHERE Dni = '$dni' ";
}

// Salida por error.
$result = mysqli_query($conn, $sql);
if (!$result) {
    //     error
    die('Query Error' . mysqli_error($conn));
}

$sql = "SELECT KP_UUID FROM Patients WHERE dni='$dni'";
$result = mysqli_query($conn, $sql);


if (!$result) {
    die('Query Error' . mysqli_error($conn));
}
while ($row = mysqli_fetch_array($result)) {
    // Capturar uuid
    $PK_UUID = $row['KP_UUID'];
}



$cols = "PK_UUID, FK_Patient, FK_EPS, FK_Ips, FK_Range, FK_Diagnosis, dni, name0,
    lastname, contactype, commentdate, commenttime, approved, sentby, statuseps, callsnumber,
    comment0, createdUser, updatedUser, exhibit_nine, exhibit_ten, send_to, observation_out" .
    ($approved == 0 ? "" : ",appointmentdate,appointmenttime");

$values = "UUID(),'$PK_UUID','$FK_EPS','$FK_Ips','$FK_Range','$FK_Diagnosis','$dni','$name',
    '$lastname','$contactype','$commentdate','$commenttime','$approved','$sentby','$statuseps','$callsnumber',
    '$comment','$username','$username','$exhibitNine','$exhibitTen','$sendTo','$commentOut'" .
    ($approved == 0 ? "" : ",'$appointmentdate','$appointmenttime'");

$sql = "INSERT INTO bitpriorities($cols) VALUES ($values)";

$result = mysqli_query($conn, $sql);

if (!$result) {
    // Error.
    die('Query Error' . mysqli_error($conn));
}

mysqli_close($conn);

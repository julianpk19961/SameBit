<?php

include 'config.php';

$PK_UUID = isset($_POST["pk_uuid"]) ? $_POST["pk_uuid"] : '';
$z_xOne = isset($_POST["z_xone"]) ? $_POST["z_xone"] : '';
$WHERE = "WHERE KP_UUID = '$PK_UUID'";

$sql = "SELECT COUNT(*) AS TOTAL FROM kardex WHERE FK_Medicine = '$PK_UUID'";
$result = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($result);
$result = $result['TOTAL'];


if ($result == 0) {
    $sql = "DELETE FROM medicines ".$WHERE;

    $icon = 'error';
    $title = 'Registro Eliminado';
    $text = 'No se encontraron movimientos en el kardex, el producto fue eliminado satisfactoriamente';
} else {
    $z_xOneNew =  $z_xOne == 1 ? 0 : 1;
    $accion =  $z_xOneNew == 1 ? 'activado' : 'inactivado';

    $sql = "UPDATE medicines SET z_xOne = '$z_xOneNew' ".$WHERE;

    $icon = 'warning';
    $title = 'Registro Actualizado';
    $text = 'El registro fue '.$accion.' correctamente' ;
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    $result = 'Query Error' . mysqli_error($conn);
} else {
    
    $result = ['icon'=> $icon , 'title'=> $title, 'text'=> $text];
    $result = json_encode($result);
}

echo $result;

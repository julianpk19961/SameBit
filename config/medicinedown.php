<?php
include 'config.php';

$PK_UUID = isset($_POST["pk_uuid"]) ? $_POST["pk_uuid"] : '';
$active  = isset($_POST["z_xone"])  ? $_POST["z_xone"]  : '';
$WHERE   = "WHERE id = '$PK_UUID'";

$sql    = "SELECT COUNT(*) AS TOTAL FROM kardex WHERE medicine_id = '$PK_UUID'";
$result = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($result);
$total  = $result['TOTAL'];

if ($total == 0) {
    $sql    = "DELETE FROM medicines " . $WHERE;
    $icon   = 'error';
    $title  = 'Registro Eliminado';
    $text   = 'No se encontraron movimientos en el kardex, el producto fue eliminado satisfactoriamente';
} else {
    $activeNew = $active == 1 ? 0 : 1;
    $accion    = $activeNew == 1 ? 'activado' : 'inactivado';
    $sql       = "UPDATE medicines SET active = '$activeNew' " . $WHERE;
    $icon      = 'warning';
    $title     = 'Registro Actualizado';
    $text      = 'El registro fue ' . $accion . ' correctamente';
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo 'Query Error' . mysqli_error($conn);
} else {
    echo json_encode(['icon' => $icon, 'title' => $title, 'text' => $text]);
}

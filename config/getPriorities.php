<?php

include 'config.php';
date_default_timezone_set('America/Bogota');

$dni = isset($_POST["dni"]) ? $_POST["dni"] : '';
$user = isset($_POST["user"]) ? $_POST["user"] : '';
$checkinStart = isset($_POST["checkinStart"]) ? $_POST["checkinStart"] : '';
$checkinEnd = isset($_POST["checkinEnd"]) ? $_POST["checkinEnd"] : '';
$checkOutStart = isset($_POST["checkOutStart"]) ? $_POST["checkOutStart"] : '';
$checkOutEnd = isset($_POST["checkOutEnd"]) ? $_POST["checkOutEnd"] : '';
$appointmentStart = isset($_POST["appointmentStart"]) ? $_POST["appointmentStart"] : '';
$appointmentEnd = isset($_POST["appointmentEnd"]) ? $_POST["appointmentEnd"] : '';

if ($checkinStart) {

    $checkinStart = date("Y-m-d H:i:s", strtotime($checkinStart));
    if ($checkinEnd) {
        $checkinEnd = date("Y-m-d H:i:s", strtotime($checkinEnd));
    }

    $where = !$checkinEnd ?
        "CAST(checkIn_date AS DATE) = '$checkinStart'" :
        "CONCAT(checkIn_date,' ',checkIn_time) BETWEEN '$checkinStart' AND '$checkinEnd'";
}

if ($checkOutStart) {

    $checkOutStart = date("Y-m-d H:i:s", strtotime($checkOutStart));
    if ($checkOutEnd) {
        $checkOutEnd = date("Y-m-d H:i:s", strtotime($checkOutEnd));
    }

    $where = !isset($where) ?: $where .= " AND ";
    $where .= !$checkOutEnd ?
        "CAST(commentdate AS DATE)= '$checkOutStart->('Y-m-d')'" :
        "CONTACT (commentdate,' ',commenttime) BETWEEN '$checkOutStart' AND '$checkOutStart'";
}

if ($appointmentStart) {

    $appointmentStart = date("Y-m-d H:i:s", strtotime($appointmentStart));
    if ($appointmentEnd) {
        $appointmentEnd = date("Y-m-d H:i:s", strtotime($appointmentEnd));
    }

    $where = !isset($where) ?: $where .= " AND ";
    $where .= !$appointmentEnd ?
        "appointmenttime ='$appointmentStart'" :
        "CONCAT (appointmentdate,' ',appointmenttime) BETWEEN '$appointmentStart' AND '$appointmentEnd'";
}



$sql = "SELECT b.checkIn_date AS RECEPCION_CORREO, b.commentdate AS RESPUESTA_CORREO, b.commenttime AS HORA_COMENTARIO, b.dni AS DOCUMENTO, CONCAT(p.Name0 ,' ', p.lastname0) AS PACIENTE, b.sentby AS ENVIADO_POR, i.name0 AS IPS, e.name0 as EPS, b.FK_Range as RANGO, b.statuseps AS ESTADO_EPS, d.codigo  AS DIAGNOSTICO, b.approved AS APROBADO, b.appointmentdate AS FECHA_CITA, b.exhibit_nine AS ANEXO_9, b.exhibit_ten AS ANEXO_10, b.send_to AS ENVIADO_A, b.comment0 AS COMENTARIO_RECEPCION, b.observation_out AS COMENTARIO_CONTRAREF, b.createdUser AS CREADO_POR, b.appointmenttime HORA_CITA, b.callsnumber AS NUMERO_LLAMADAS, b.updatedUser AS ACTUALIZADO_POR, b.updated AS HORA_ACTUALIZADO, b.contactype AS TIPO_CONTACTO FROM bitpriorities b INNER JOIN patients p ON b.FK_Patient = p.KP_UUID INNER JOIN entities e ON b.FK_EPS = e.PK_UUID INNER JOIN entities i ON b.FK_Ips = i.PK_UUID INNER JOIN diagnosis d ON b.FK_Diagnosis = d.KP_UUID";

$sql .= " WHERE " . $where;
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
} else {

    $data = mysqli_fetch_assoc($result);

    while ($data = mysqli_fetch_assoc($result)) {
        // en caso de tener problemas con la ñ y carácteres latam
        // $array['data'][] = array_map("utf8_encode", $data);
        $array['data'][] = $data;
    }

    // }
    echo json_encode(isset($array) ? $array : $array['data'][] = null);
}
// mysqli_free_result($result);
mysqli_close($conn);

<?php

use LDAP\Result;

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

    // $checkinStart = date("Y-m-d H:i:s", strtotime($checkinStart));
    if ($checkinEnd) {
        $checkinEnd = date("Y-m-d H:i:s", strtotime($checkinEnd));
    }

    $checkinStart = date(!$checkinEnd ? "Y-m-d" : "Y-m-d H:i:s", strtotime($checkinStart));

    $where = !$checkinEnd ?
        "CAST(b.checkIn_date AS DATE) = '$checkinStart'" :
        "CONCAT(b.checkIn_date,' ',b.checkIn_time) BETWEEN '$checkinStart' AND '$checkinEnd'";
}

if ($checkOutStart) {

    if ($checkOutEnd) {
        $checkOutEnd = date("Y-m-d H:i:s", strtotime($checkOutEnd));
    }

    $checkOutStart = date(!$checkOutEnd ? "Y-m-d" : "Y-m-d H:i:s", strtotime($checkOutStart));

    $where = !isset($where) ? "" : $where .= " AND ";

    $where .= !$checkOutEnd ?
        "CAST(b.commentdate AS DATE)= '$checkOutStart'" :
        "CONCAT(b.commentdate,' ',b.commenttime) BETWEEN '$checkOutStart' AND '$checkOutEnd'";
}

if ($appointmentStart) {

    if ($appointmentEnd) {
        $appointmentEnd = date("Y-m-d H:i:s", strtotime($appointmentEnd));
    }

    $appointmentStart = date(!$appointmentEnd ? "Y-m-d" : "Y-m-d H:i:s", strtotime($appointmentStart));

    $where = !isset($where) ? "" : $where .= " AND ";
    $where .= !$appointmentEnd ?
        "CAST(b.appointmentdate AS DATE) ='$appointmentStart'" :
        "CONCAT(b.appointmentdate,' ',b.appointmenttime) BETWEEN '$appointmentStart' AND '$appointmentEnd'";
}

if ($dni) {

    $where = !isset($where) ? "" : $where .= " AND ";
    $where .= is_numeric($dni) ? "b.dni" : "CONCAT(p.Name0,' ',p.lastname0)";
    $where .= " LIKE '%$dni%'";
}

if ($user) {

    $where = !isset($where) ? "" : $where .= " AND ";
    $where .= "b.createdUser LIKE '%$user%'";
}

$sql = "SELECT b.checkIn_date AS RECEPCION_CORREO,b.checkIn_time AS HORA_RECEPCION, b.commentdate AS RESPUESTA_CORREO, b.commenttime AS HORA_RESPUESTA, b.dni AS DOCUMENTO, CONCAT(p.Name0 ,' ', p.lastname0) AS PACIENTE, b.sentby AS ENVIADO_POR, i.name0 AS IPS, e.name0 as EPS, b.FK_Range as RANGO, b.statuseps AS ESTADO_EPS, d.codigo  AS DIAGNOSTICO, b.approved AS APROBADO, b.appointmentdate AS FECHA_CITA, b.exhibit_nine AS ANEXO_9, b.exhibit_ten AS ANEXO_10, b.send_to AS ENVIADO_A, b.comment0 AS COMENTARIO_RECEPCION, b.observation_out AS COMENTARIO_CONTRAREF, b.createdUser AS CREADO_POR, b.appointmenttime HORA_CITA, b.callsnumber AS NUMERO_LLAMADAS, b.updatedUser AS ACTUALIZADO_POR, b.updated AS HORA_ACTUALIZADO, b.contactype AS TIPO_CONTACTO, b.response_days AS DIAS_RESPUESTA,b.response_time AS HORAS_RESPUESTA, b.attention_days AS DIAS_CITA,b.attention_time AS HORAS_CITA FROM bitpriorities b INNER JOIN patients p ON b.FK_Patient = p.KP_UUID INNER JOIN entities e ON b.FK_EPS = e.PK_UUID INNER JOIN entities i ON b.FK_Ips = i.PK_UUID INNER JOIN diagnosis d ON b.FK_Diagnosis = d.KP_UUID";
$sql .= " WHERE " . $where;

// echo var_dump($sql);
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
} else {

    // $data = mysqli_fetch_assoc($result);
    while ($data = mysqli_fetch_assoc($result)) {
        // en caso de tener problemas con la ñ y carácteres latam
        // $array['data'][] = array_map("utf8_encode", $data);
        $array['data'][] = $data;
    }

    echo json_encode(!isset($array) ? $array['data'][] = null : $array);
}
// mysqli_free_result($result);
mysqli_close($conn);

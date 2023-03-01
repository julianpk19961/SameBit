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

    $checkInDateWhere = !$checkinEnd ?
        "checkIn_date = $checkinStart" :
        "CONCAT(checkIn_date,' ',checkIn_time) BETWEEN '$checkinStart' AND '$checkinEnd'";
}

if ($checkOutStart and !$checkinEnd) {
    $checkOutStart = date("Y-m-d H:i:s", strtotime($checkOutStart));
    // $checkinEnd = date_time_set($checkOutStart, 23, 45, 49);
}

if ($appointmentStart) {
    $appointmentStart = date("Y-m-d H:i:s", strtotime($appointmentStart));
    // $chappointmentEndckinEnd = date_time_set($appointmentStart, 23, 45, 49);

}

$sql = "SELECT CONCAT(p.Name0 ,' ',p.lastname0) as PACIENTE, e.name0 as EPS,
b.statuseps ESTADO_EPS, b.FK_Range as RANGO, i.name0 AS IPS, d.codigo  AS DIAGNOSTICO,
b.dni AS CC, b.name0 AS NOMBRE, b.lastname AS PELLIDO, b.sentby AS ENVIADO_POR,
b.comment0 AS COMENTARIO_RECEPCION, b.commentdate AS RESPUESTA_CORREO,b.commenttime AS HORA_COMENTARIO,
b.approved APROBADO, b.appointmentdate AS FECHA_CITA, b.appointmenttime HORA_CITA,
b.callsnumber AS NUMERO_LLAMADAS,b.createdUser AS CREADO_POR,b.created AS RECEPCION_CORREO,
b.updatedUser AS ACTUALIZADO_POR,b.updated AS HORA_ACTUALIZADO, b.contactype AS TIPO_CONTACTO,
b.exhibit_nine AS ANEXO_9, b.exhibit_ten AS ANEXO_10, b.send_to AS ENVIADO_A, b.observation_out AS COMENTARIO_CONTRAREF

FROM bitpriorities b
INNER JOIN patients p
ON b.FK_Patient = p.KP_UUID
INNER JOIN entities e
ON b.FK_EPS = e.PK_UUID
INNER JOIN entities i
ON b.FK_Ips = i.PK_UUID
INNER JOIN diagnosis d
ON b.FK_Diagnosis = d.KP_UUID

WHERE $checkInDateWhere
";

$data['data'] = [
    'dni' => $dni,
    'user' => $user,
    'checkinStart' => $checkinStart,
    'checkinEnd' => $checkinEnd,
    'checkInDateWhere' => $checkInDateWhere,
    'checkOutStart' => $checkOutStart,
    'checkOutEnd' => $checkOutEnd,
    'appointmentStart' => $appointmentStart,
    'appointmentEnd' => $appointmentEnd,
    'sql' => $sql,
];
echo print_r($data);
return false;

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
} else {
    while ($data = mysqli_fetch_assoc($result)) {
        //en caso de tener problemas con la ñ y carácteres latam
        // $array['data'][] = array_map("utf8_encode",$data);
        $array['data'][] = $data;
    }
    echo json_encode($array);
}
// mysqli_free_result($result);
mysqli_close($conn);

<?php

use LDAP\Result;

include 'config.php';
date_default_timezone_set('America/Bogota');


$dni = isset($_POST["dni"]) ? mysqli_real_escape_string($conn, $_POST["dni"]) : '';
$user = isset($_POST["user"]) ? mysqli_real_escape_string($conn, $_POST["user"]) : '';
$checkinStart = isset($_POST["checkinStart"]) ? mysqli_real_escape_string($conn, $_POST["checkinStart"]) : '';
$checkinEnd = isset($_POST["checkinEnd"]) ? mysqli_real_escape_string($conn, $_POST["checkinEnd"]) : '';
$checkOutStart = isset($_POST["checkOutStart"]) ? mysqli_real_escape_string($conn, $_POST["checkOutStart"]) : '';
$checkOutEnd = isset($_POST["checkOutEnd"]) ? mysqli_real_escape_string($conn, $_POST["checkOutEnd"]) : '';
$appointmentStart = isset($_POST["appointmentStart"]) ? mysqli_real_escape_string($conn, $_POST["appointmentStart"]) : '';
$appointmentEnd = isset($_POST["appointmentEnd"]) ? mysqli_real_escape_string($conn, $_POST["appointmentEnd"]) : '';

$where = array();

if ($checkinStart) {
    if ($checkinEnd) {
        $checkinEnd = date("Y-m-d H:i:s", strtotime($checkinEnd));
    }

    $checkinStart = date(!$checkinEnd ? "Y-m-d" : "Y-m-d H:i:s", strtotime($checkinStart));

    $where[] = !$checkinEnd ?
        "b.checkIn_date = DATE('$checkinStart')" :
        "TIMESTAMP(b.checkIn_date, b.checkIn_time) BETWEEN '$checkinStart' AND '$checkinEnd'";
}

if ($checkOutStart) {

    if ($checkOutEnd) {
        $checkOutEnd = date("Y-m-d H:i:s", strtotime($checkOutEnd));
    }

    $checkOutStart = date(!$checkOutEnd ? "Y-m-d" : "Y-m-d H:i:s", strtotime($checkOutStart));

    $where[] = !$checkOutEnd ?
        "b.commentdate = DATE( '$checkOutStart')" :
        "TIMESTAMP(b.commentdate, b.commenttime) BETWEEN '$checkOutStart' AND '$checkOutEnd'";
}

if ($appointmentStart) {

    if ($appointmentEnd) {
        $appointmentEnd = date("Y-m-d H:i:s", strtotime($appointmentEnd));
    }

    $appointmentStart = date(!$appointmentEnd ? "Y-m-d" : "Y-m-d H:i:s", strtotime($appointmentStart));

    $where[] = !$appointmentEnd ?
        "b.appointmentdate = DATE ('$appointmentStart')" :
        "TIMESTAMP(b.appointmentdate, b.appointmenttime) BETWEEN '$appointmentStart' AND '$appointmentEnd'";
}

if ($dni) {
    $where[] = is_numeric($dni) ? "b.dni LIKE '%$dni%'" : "CONCAT(p.Name0,' ',p.lastname0) LIKE '%$dni%'";
}

if ($user) {
    $where[] = "b.createdUser LIKE '%$user%'";
}

$whereClause = '';
if (!empty($where)) {
    $whereClause = ' WHERE ' . implode(' AND ', $where);
}


$sql = "SELECT b.PK_UUID FROM bitpriorities b";
if (!empty($whereClause)) {
    $sql .= $whereClause;
}

$headers_result = mysqli_query($conn, $sql);
if ($headers_result) {
    $data_header = array();

    while ($row = mysqli_fetch_assoc($headers_result)) {
        $data_header[] = $row['PK_UUID'];
    }

    if (isset($data_header)) {

        #Consulta sql para traer los datos asociados a los pk encontrados.
        $sql = "SELECT
        b.checkIn_date AS RECEPCION_CORREO,
        b.checkIn_time AS HORA_RECEPCION,
        b.commentdate AS RESPUESTA_CORREO,
        b.commenttime AS HORA_RESPUESTA,
        b.dni AS DOCUMENTO,
        CONCAT(b.Name0, ' ', b.lastname) AS PACIENTE,
        b.sentby AS ENVIADO_POR,
        i.name0 AS IPS,
        e.name0 AS EPS,
        b.FK_Range AS RANGO,
        b.statuseps AS ESTADO_EPS,
        d.codigo AS DIAGNOSTICO,
        b.approved AS APROBADO,
        b.appointmentdate AS FECHA_CITA,
        b.exhibit_nine AS ANEXO_9,
        b.exhibit_ten AS ANEXO_10,
        b.send_to AS ENVIADO_A,
        b.comment0 AS COMENTARIO_RECEPCION,
        b.observation_out AS COMENTARIO_CONTRAREF,
        b.createdUser AS CREADO_POR,
        b.appointmenttime HORA_CITA,
        b.callsnumber AS NUMERO_LLAMADAS,
        b.updatedUser AS ACTUALIZADO_POR,
        b.updated AS HORA_ACTUALIZADO,
        b.contactype AS TIPO_CONTACTO,
        b.response_days AS DIAS_RESPUESTA,
        b.response_time AS HORAS_RESPUESTA,
        b.attention_days AS DIAS_CITA,
        b.attention_time AS HORAS_CITA
    FROM
        bitpriorities b
    -- INNER JOIN patients p ON
    --     b.FK_Patient = p.KP_UUID
    INNER JOIN entities e ON
        b.FK_EPS = e.PK_UUID
    INNER JOIN entities i ON
        b.FK_Ips = i.PK_UUID
    INNER JOIN diagnosis d ON
        b.FK_Diagnosis = d.KP_UUID";

        $sql .= " WHERE b.PK_UUID IN('" . implode("', '", $data_header) . "')";
        $body_result = mysqli_query($conn, $sql);

        if ($body_result) {
            $data_body = array();

            while ($row = mysqli_fetch_assoc($body_result)) {
                $data_body[] = $row;
            }
        }

        $data = array(
            'data' => $data_body
        );

        echo json_encode(!isset($data) ? $data['data'][] = null : $data);
    } else {
        echo 'Querry Error:' . mysqli_error($conn);
    }
} else {
    echo "No result";
}


mysqli_close($conn);
return false;

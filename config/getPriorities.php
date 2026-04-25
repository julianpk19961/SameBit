<?php
include 'config.php';
date_default_timezone_set('America/Bogota');

$dni            = isset($_POST["dni"])            ? mysqli_real_escape_string($conn, $_POST["dni"])            : '';
$user           = isset($_POST["user"])           ? mysqli_real_escape_string($conn, $_POST["user"])           : '';
$checkinStart   = isset($_POST["checkinStart"])   ? mysqli_real_escape_string($conn, $_POST["checkinStart"])   : '';
$checkinEnd     = isset($_POST["checkinEnd"])     ? mysqli_real_escape_string($conn, $_POST["checkinEnd"])     : '';
$checkOutStart  = isset($_POST["checkOutStart"])  ? mysqli_real_escape_string($conn, $_POST["checkOutStart"])  : '';
$checkOutEnd    = isset($_POST["checkOutEnd"])    ? mysqli_real_escape_string($conn, $_POST["checkOutEnd"])    : '';
$appointmentStart = isset($_POST["appointmentStart"]) ? mysqli_real_escape_string($conn, $_POST["appointmentStart"]) : '';
$appointmentEnd   = isset($_POST["appointmentEnd"])   ? mysqli_real_escape_string($conn, $_POST["appointmentEnd"])   : '';

$where = array();

if ($checkinStart) {
    if ($checkinEnd) {
        $checkinEnd = date("Y-m-d H:i:s", strtotime($checkinEnd));
    }
    $checkinStart = date(!$checkinEnd ? "Y-m-d" : "Y-m-d H:i:s", strtotime($checkinStart));
    $where[] = !$checkinEnd
        ? "b.checkin_date = DATE('$checkinStart')"
        : "TIMESTAMP(b.checkin_date, b.checkin_time) BETWEEN '$checkinStart' AND '$checkinEnd'";
}

if ($checkOutStart) {
    if ($checkOutEnd) {
        $checkOutEnd = date("Y-m-d H:i:s", strtotime($checkOutEnd));
    }
    $checkOutStart = date(!$checkOutEnd ? "Y-m-d" : "Y-m-d H:i:s", strtotime($checkOutStart));
    $where[] = !$checkOutEnd
        ? "b.response_date = DATE('$checkOutStart')"
        : "TIMESTAMP(b.response_date, b.response_time) BETWEEN '$checkOutStart' AND '$checkOutEnd'";
}

if ($appointmentStart) {
    if ($appointmentEnd) {
        $appointmentEnd = date("Y-m-d H:i:s", strtotime($appointmentEnd));
    }
    $appointmentStart = date(!$appointmentEnd ? "Y-m-d" : "Y-m-d H:i:s", strtotime($appointmentStart));
    $where[] = !$appointmentEnd
        ? "b.appointment_date = DATE('$appointmentStart')"
        : "TIMESTAMP(b.appointment_date, b.appointment_time) BETWEEN '$appointmentStart' AND '$appointmentEnd'";
}

if ($dni) {
    $where[] = is_numeric($dni)
        ? "b.document_number LIKE '%$dni%'"
        : "CONCAT(b.first_name, ' ', b.last_name) LIKE '%$dni%'";
}

if ($user) {
    $where[] = "b.created_by LIKE '%$user%'";
}

$whereClause = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT b.id FROM priorities b" . $whereClause;
$headers_result = mysqli_query($conn, $sql);

if ($headers_result) {
    $data_header = array();
    while ($row = mysqli_fetch_assoc($headers_result)) {
        $data_header[] = $row['id'];
    }

    if (isset($data_header)) {
        $sql = "SELECT
            b.checkin_date        AS RECEPCION_CORREO,
            b.checkin_time        AS HORA_RECEPCION,
            b.response_date       AS RESPUESTA_CORREO,
            b.response_time       AS HORA_RESPUESTA,
            b.document_number     AS DOCUMENTO,
            CONCAT(b.first_name, ' ', b.last_name) AS PACIENTE,
            b.sent_by             AS ENVIADO_POR,
            i.name                AS IPS,
            e.name                AS EPS,
            b.range_level         AS RANGO,
            b.eps_status          AS ESTADO_EPS,
            d.code                AS DIAGNOSTICO,
            b.approved            AS APROBADO,
            b.appointment_date    AS FECHA_CITA,
            b.annex_nine          AS ANEXO_9,
            b.annex_ten           AS ANEXO_10,
            b.sent_to             AS ENVIADO_A,
            b.reception_notes     AS COMENTARIO_RECEPCION,
            b.outgoing_notes      AS COMENTARIO_CONTRAREF,
            b.created_by          AS CREADO_POR,
            b.appointment_time    AS HORA_CITA,
            b.calls_count         AS NUMERO_LLAMADAS,
            b.updated_by          AS ACTUALIZADO_POR,
            b.updated_at          AS HORA_ACTUALIZADO,
            b.contact_type        AS TIPO_CONTACTO,
            b.response_day_diff   AS DIAS_RESPUESTA,
            b.response_hour_diff  AS HORAS_RESPUESTA,
            b.attention_day_diff  AS DIAS_CITA,
            b.attention_hour_diff AS HORAS_CITA
        FROM priorities b
        INNER JOIN entities e  ON b.eps_id       = e.id
        INNER JOIN entities i  ON b.ips_id        = i.id
        INNER JOIN diagnoses d ON b.diagnosis_id  = d.id";

        $sql .= " WHERE b.id IN('" . implode("', '", $data_header) . "')";
        $body_result = mysqli_query($conn, $sql);

        if ($body_result) {
            $data_body = array();
            while ($row = mysqli_fetch_assoc($body_result)) {
                $data_body[] = $row;
            }
        }

        $data = array('data' => $data_body);
        echo json_encode(!isset($data) ? $data['data'][] = null : $data);
    } else {
        echo 'Query Error:' . mysqli_error($conn);
    }
} else {
    echo "No result";
}

mysqli_close($conn);
return false;

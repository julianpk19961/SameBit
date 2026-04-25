<?php
include 'config.php';
include 'setup.php';
include 'phpMail.php';
include '../PHPExcel/Classes/PHPExcel.php';
date_default_timezone_set('America/Bogota');

$dateQuery = date('Y-m-d', strtotime('-1 days'));

$sql = "SELECT b.id FROM priorities b
        WHERE TIMESTAMP(b.response_date, b.response_time)
        BETWEEN '$dateQuery 00:00:00' AND '$dateQuery 23:59:59'";

$headers_result = mysqli_query($conn, $sql);
if ($headers_result === false) {
    echo 'Error: ' . mysqli_error($conn);
} else {
    while ($row = mysqli_fetch_assoc($headers_result)) {
        $data_header[] = $row['id'];
    }

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
        INNER JOIN entities e  ON b.eps_id      = e.id
        INNER JOIN entities i  ON b.ips_id       = i.id
        INNER JOIN diagnoses d ON b.diagnosis_id = d.id";

    $sql .= " WHERE b.id IN('" . implode("', '", $data_header) . "') ORDER BY HORA_RESPUESTA";
    $body_result = mysqli_query($conn, $sql);

    if ($body_result) {
        $dateMail     = date('Y-m-d');
        $timeMail     = date('H:i:s');
        $dateFileName = date_create($dateQuery) < date_create($dateMail) ? $dateQuery : $dateMail;
        $txtDateEmail = date_create($dateQuery) < date_create($dateMail) ? $dateQuery : 'de hoy';

        try {
            $filename = "../documentos/$dateFileName.xlsx";
            $file     = new PHPExcel();
            $file->getActiveSheet()->setTitle("Reporte-Prioritaria-$dateFileName");

            $columns = array_keys(mysqli_fetch_assoc($body_result));
            $file->getActiveSheet()->fromArray($columns, null, 'A1');
            $rowNumber = 2;

            while ($row = mysqli_fetch_assoc($body_result)) {
                $file->getActiveSheet()->fromArray($row, null, 'A' . $rowNumber);
                $rowNumber++;
            }

            $fileStore = PHPExcel_IOFactory::createWriter($file, 'Excel2007');
            $fileStore->save($dateFileName);
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage(), PHP_EOL;
        }

        $body = "Buen día Dr/Dres,\nEste correo electrónico se envió el $dateMail a las $timeMail\n\nSe adjunta reporte de prioritaria correspondiente a los registros ingresados por los APH el día $txtDateEmail.\n\n\n$appName";
        $correoHandler = new CorreoHandler();
        $correoHandler->enviarCorreo(
            'direccionmedica@samein.com.co',
            "Reporte de prioritaria $dateQuery",
            $body,
            ['path' => $dateFileName, 'name' => "$dateFileName.xlsx"]
        );
    }
}

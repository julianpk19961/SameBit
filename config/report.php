<?php

include 'config.php';
include 'phpMail.php';

date_default_timezone_set('America/Bogota');


$ini =  date('Y-m-d 00:00:00');
$end =  date('Y-m-d 23:59:59');

$sql = "SELECT b.PK_UUID FROM bitpriorities b WHERE TIMESTAMP(b.commentdate, b.commenttime) BETWEEN '$ini' AND '$end'";

$headers_result = mysqli_query($conn, $sql);
if ($headers_result === false) {
    echo 'Error: ' . mysqli_error($conn);
} else {
    #Consulta sql para traer los datos asociados a los pk encontrados.
    while ($row = mysqli_fetch_assoc($headers_result)) {
        $data_header[] = $row['PK_UUID'];
    }

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

    $sql .= " WHERE b.PK_UUID IN('" . implode("', '", $data_header) . "') ORDER BY HORA_RESPUESTA";
    $body_result = mysqli_query($conn, $sql);

    if ($body_result) {
        $data_body = array();

        $fulltimeMail = date_create(date('Y-m-d H:i:s'));
        $dateMail = $fulltimeMail->format('Y-m-d');
        $timeMail = $fulltimeMail->format('H:i:s');

        $filename = "../documentos/$dateMail.csv";
        $file = fopen($filename, "w");

        $columns = array_keys(mysqli_fetch_assoc($body_result));
        fputcsv($file, $columns);

        #columnas
        while ($row = mysqli_fetch_assoc($body_result)) {
            $data_body[] = $row;
        }

        foreach ($data_body as $row) {
            fputcsv($file, $row);
        }

        fclose($file);

        $body = "Buen día Dr/Dres,\nEste correo electrónico se envió el $dateMail a las $timeMail\n\nSe adjunta reporte de prioritaria correspondiente a los registros ingresados por los APH el día de hoy. \n\n\nSamebit";
        $correoHandler = new CorreoHandler();
        $correoHandler->enviarCorreo(
            'julianrodriguez19961@gmail.com',
            'Reporte de prioritaria',
            "$body",
            ['path' => $filename, 'name' => "$dateMail"]
        );
    }
}

<?php

include 'config.php';

$sql = "SELECT p.Name0 as PACIENTE, e.name0 as EPS,b.statuseps ESTADO_EPS, b.FK_Range as RANGO, i.name0 AS IPS, d.codigo  AS DIAGNOSTICO, b.dni AS CC, b.name0 AS NOMBRE, b.lastname AS PELLIDO, b.sentby AS ENVIADO_POR, b.comment0 AS COMENTARIO, b.commentdate AS FECHA_COMENTARIO,b.commenttime AS HORA_COMENTARIO, b.approved APROBADO, b.appointmentdate AS FECHA_CITA, b.appointmenttime HORA_CITA,b.callsnumber AS NUMERO_LLAMADAS,b.createdUser AS CREADO_POR,b.created AS HORA_CREADO,b.updatedUser AS ACTUALIZADO_POR,b.updated AS HORA_ACTUALIZADO, b.contactype AS TIPO_CONTACTO
FROM bitpriorities b
INNER JOIN patients p
ON b.FK_Patient = p.KP_UUID
INNER JOIN entities e
ON b.FK_EPS = e.PK_UUID
INNER JOIN entities i
ON b.FK_Ips = i.PK_UUID
INNER JOIN diagnosis d
ON b.FK_Diagnosis = d.KP_UUID";


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
mysqli_free_result($result);
mysqli_close($conn);

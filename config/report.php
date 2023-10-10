<?php
//Activar en caso de necesitar detectar errores.

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

//Librerias y archivo de conección
include 'config.php';
include 'phpMail.php';
include '../PHPExcel/Classes/PHPExcel.php';
//Configuración de zona horaria.
date_default_timezone_set('America/Bogota');

//Fecha consulta Sql - Formato Año-mes-dia
$dateQuery = date('Y-m-d', strtotime('-1 days'));
// $dateQuery = date('2023-10-08');

$sql = "SELECT b.PK_UUID FROM bitpriorities b WHERE TIMESTAMP(b.commentdate, b.commenttime) BETWEEN '$dateQuery 00:00:00' AND '$dateQuery 23:59:59'";

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

        // Datos de información para correo eléctronico.
        $dateMail = date('Y-m-d');
        $timeMail = date('H:i:s');

        //Definir el nombre del archivo basado en la fecha.
        // se hace de esta forma por la versión de php.
        $dateFileName = date_create($dateQuery) < date_create($dateMail) ? $dateQuery : $dateMail;
        $txtDateEmail = date_create($dateQuery) < date_create($dateMail) ? $dateQuery : 'de hoy';


        try {

            $filename = "../documentos/$dateFileName.xlsx";

            // Creación Objeto
            $file = new PHPExcel();
            $file->getActiveSheet()->setTitle("Reporte-Prioritaria-$dateFileName");

            // Definir en la línea A1 el nombre de las columnas.
            $columns = array_keys(mysqli_fetch_assoc($body_result));
            $file->getActiveSheet()->fromArray($columns, null, 'A1');
            // Moverme a la línea número 2
            $rowNumber = 2;

            //Recorrer el resultado para insertar sus valores en el documento desde la columna A
            while ($row = mysqli_fetch_assoc($body_result)) {
                $file->getActiveSheet()->fromArray($row, null, 'A' . $rowNumber);
                $rowNumber++;
            }


            // Creción de documento: Formatos permitidos además de Excel5,Excel2007,PDF,HTML,CSV,Tab-Delimited-Text: 
            $fileStore = PHPExcel_IOFactory::createWriter($file, 'Excel2007');
            // Guardar el documento
            $fileStore->save($dateFileName);
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage(), PHP_EOL;
        }

        $body = "Buen día Dr/Dres,\nEste correo electrónico se envió el $dateMail a las $timeMail\n\nSe adjunta reporte de prioritaria correspondiente a los registros ingresados por los APH el día $txtDateEmail. \n\n\nSamebit";
        $correoHandler = new CorreoHandler();
        $correoHandler->enviarCorreo(
            'direccionmedica@samein.com.co',
            "Reporte de prioritaria $dateQuery",
            "$body",
            [
                'path' => $dateFileName,
                'name' => "$dateFileName.xlsx"
            ]
        );
    }
}

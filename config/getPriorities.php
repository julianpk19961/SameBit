<?php
/**
 * getPriorities.php - Reporte seguro de prioridades/llamadas
 * 
 * Usa prepared statements para prevenir SQL injection
 * y retorna resultados en formato JSON para DataTables
 */

require_once 'config.php';

header('Content-Type: application/json; charset=UTF-8');

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Obtener y sanitizar parámetros
$dni = isset($_POST["dni"]) ? trim($_POST["dni"]) : '';
$user = isset($_POST["user"]) ? trim($_POST["user"]) : '';
$checkinStart = isset($_POST["checkinStart"]) ? trim($_POST["checkinStart"]) : '';
$checkinEnd = isset($_POST["checkinEnd"]) ? trim($_POST["checkinEnd"]) : '';
$checkOutStart = isset($_POST["checkOutStart"]) ? trim($_POST["checkOutStart"]) : '';
$checkOutEnd = isset($_POST["checkOutEnd"]) ? trim($_POST["checkOutEnd"]) : '';
$appointmentStart = isset($_POST["appointmentStart"]) ? trim($_POST["appointmentStart"]) : '';
$appointmentEnd = isset($_POST["appointmentEnd"]) ? trim($_POST["appointmentEnd"]) : '';

// Array para parámetros de prepared statement
$params = [];
$types = '';
$where_conditions = [];

// Validar y construir cláusula WHERE para checkin
if ($checkinStart) {
    if ($checkinEnd) {
        // Validar formato de fecha
        $checkinStart_dt = DateTime::createFromFormat('Y-m-d\TH:i:s', $checkinStart);
        $checkinEnd_dt = DateTime::createFromFormat('Y-m-d\TH:i:s', $checkinEnd);
        
        if ($checkinStart_dt && $checkinEnd_dt) {
            $where_conditions[] = "TIMESTAMP(b.checkin_date, b.checkin_time) BETWEEN ? AND ?";
            $params[] = $checkinStart_dt->format('Y-m-d H:i:s');
            $params[] = $checkinEnd_dt->format('Y-m-d H:i:s');
            $types .= 'ss';
        }
    } else {
        $checkinStart_dt = DateTime::createFromFormat('Y-m-d\TH:i:s', $checkinStart);
        if ($checkinStart_dt) {
            $where_conditions[] = "b.checkin_date = DATE(?)";
            $params[] = $checkinStart_dt->format('Y-m-d H:i:s');
            $types .= 's';
        }
    }
}

// Validar y construir cláusula WHERE para checkout
if ($checkOutStart) {
    if ($checkOutEnd) {
        $checkOutStart_dt = DateTime::createFromFormat('Y-m-d\TH:i:s', $checkOutStart);
        $checkOutEnd_dt = DateTime::createFromFormat('Y-m-d\TH:i:s', $checkOutEnd);
        
        if ($checkOutStart_dt && $checkOutEnd_dt) {
            $where_conditions[] = "TIMESTAMP(b.response_date, b.response_time) BETWEEN ? AND ?";
            $params[] = $checkOutStart_dt->format('Y-m-d H:i:s');
            $params[] = $checkOutEnd_dt->format('Y-m-d H:i:s');
            $types .= 'ss';
        }
    } else {
        $checkOutStart_dt = DateTime::createFromFormat('Y-m-d\TH:i:s', $checkOutStart);
        if ($checkOutStart_dt) {
            $where_conditions[] = "b.response_date = DATE(?)";
            $params[] = $checkOutStart_dt->format('Y-m-d H:i:s');
            $types .= 's';
        }
    }
}

// Validar y construir cláusula WHERE para appointment
if ($appointmentStart) {
    if ($appointmentEnd) {
        $appointmentStart_dt = DateTime::createFromFormat('Y-m-d\TH:i:s', $appointmentStart);
        $appointmentEnd_dt = DateTime::createFromFormat('Y-m-d\TH:i:s', $appointmentEnd);
        
        if ($appointmentStart_dt && $appointmentEnd_dt) {
            $where_conditions[] = "TIMESTAMP(b.appointment_date, b.appointment_time) BETWEEN ? AND ?";
            $params[] = $appointmentStart_dt->format('Y-m-d H:i:s');
            $params[] = $appointmentEnd_dt->format('Y-m-d H:i:s');
            $types .= 'ss';
        }
    } else {
        $appointmentStart_dt = DateTime::createFromFormat('Y-m-d\TH:i:s', $appointmentStart);
        if ($appointmentStart_dt) {
            $where_conditions[] = "b.appointment_date = DATE(?)";
            $params[] = $appointmentStart_dt->format('Y-m-d H:i:s');
            $types .= 's';
        }
    }
}

// Validar y construir cláusula WHERE para DNI/nombre
if ($dni) {
    if (is_numeric($dni)) {
        $where_conditions[] = "b.document_number LIKE ?";
        $params[] = '%' . $dni . '%';
        $types .= 's';
    } else {
        $where_conditions[] = "(CONCAT(b.first_name, ' ', b.last_name) LIKE ? OR b.document_number LIKE ?)";
        $search_name = '%' . $dni . '%';
        $params[] = $search_name;
        $params[] = $search_name;
        $types .= 'ss';
    }
}

// Validar y construir cláusula WHERE para usuario
if ($user) {
    $where_conditions[] = "b.created_by LIKE ?";
    $params[] = '%' . $user . '%';
    $types .= 's';
}

// Construir cláusula WHERE
$where_clause = !empty($where_conditions) ? ' WHERE ' . implode(' AND ', $where_conditions) : '';

// Consulta principal para obtener IDs (primero)
$sql_ids = "SELECT b.id FROM priorities b" . $where_clause . " LIMIT 1000";

$stmt_ids = $conn->prepare($sql_ids);
if (!$stmt_ids) {
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
    exit;
}

if (!empty($params)) {
    $stmt_ids->bind_param($types, ...$params);
}

$stmt_ids->execute();
$result_ids = $stmt_ids->get_result();

if (!$result_ids) {
    $stmt_ids->close();
    echo json_encode(['error' => 'Error en la consulta']);
    exit;
}

// Obtener IDs
$ids = [];
while ($row = $result_ids->fetch_assoc()) {
    $ids[] = $row['id'];
}

$stmt_ids->close();

// Si no hay resultados, retornar vacío
if (empty($ids)) {
    echo json_encode(['data' => []]);
    exit;
}

// Consulta principal con JOINs
$sql_main = "SELECT
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
INNER JOIN entities e ON b.eps_id = e.id
INNER JOIN entities i ON b.ips_id = i.id
INNER JOIN diagnoses d ON b.diagnosis_id = d.id
WHERE b.id IN (" . implode(',', array_fill(0, count($ids), '?')) . ")";

$stmt_main = $conn->prepare($sql_main);
if (!$stmt_main) {
    echo json_encode(['error' => 'Error en la consulta principal: ' . $conn->error]);
    exit;
}

// Bind IDs
$ids_types = str_repeat('s', count($ids));
$stmt_main->bind_param($ids_types, ...$ids);
$stmt_main->execute();
$result_main = $stmt_main->get_result();

if (!$result_main) {
    $stmt_main->close();
    echo json_encode(['error' => 'Error en la consulta principal']);
    exit;
}

// Procesar resultados
$data_body = [];
while ($row = $result_main->fetch_assoc()) {
    $data_body[] = [
        'RECEPCION_CORREO' => htmlspecialchars($row['RECEPCION_CORREO'] ?? ''),
        'HORA_RECEPCION' => htmlspecialchars($row['HORA_RECEPCION'] ?? ''),
        'RESPUESTA_CORREO' => htmlspecialchars($row['RESPUESTA_CORREO'] ?? ''),
        'HORA_RESPUESTA' => htmlspecialchars($row['HORA_RESPUESTA'] ?? ''),
        'DOCUMENTO' => htmlspecialchars($row['DOCUMENTO'] ?? ''),
        'PACIENTE' => htmlspecialchars($row['PACIENTE'] ?? ''),
        'ENVIADO_POR' => htmlspecialchars($row['ENVIADO_POR'] ?? ''),
        'IPS' => htmlspecialchars($row['IPS'] ?? ''),
        'EPS' => htmlspecialchars($row['EPS'] ?? ''),
        'RANGO' => htmlspecialchars($row['RANGO'] ?? ''),
        'ESTADO_EPS' => htmlspecialchars($row['ESTADO_EPS'] ?? ''),
        'DIAGNOSTICO' => htmlspecialchars($row['DIAGNOSTICO'] ?? ''),
        'APROBADO' => htmlspecialchars($row['APROBADO'] ?? ''),
        'FECHA_CITA' => htmlspecialchars($row['FECHA_CITA'] ?? ''),
        'ANEXO_9' => htmlspecialchars($row['ANEXO_9'] ?? ''),
        'ANEXO_10' => htmlspecialchars($row['ANEXO_10'] ?? ''),
        'ENVIADO_A' => htmlspecialchars($row['ENVIADO_A'] ?? ''),
        'COMENTARIO_RECEPCION' => htmlspecialchars($row['COMENTARIO_RECEPCION'] ?? ''),
        'COMENTARIO_CONTRAREF' => htmlspecialchars($row['COMENTARIO_CONTRAREF'] ?? ''),
        'CREADO_POR' => htmlspecialchars($row['CREADO_POR'] ?? ''),
        'HORA_CITA' => htmlspecialchars($row['HORA_CITA'] ?? ''),
        'NUMERO_LLAMADAS' => htmlspecialchars($row['NUMERO_LLAMADAS'] ?? ''),
        'ACTUALIZADO_POR' => htmlspecialchars($row['ACTUALIZADO_POR'] ?? ''),
        'HORA_ACTUALIZADO' => htmlspecialchars($row['HORA_ACTUALIZADO'] ?? ''),
        'TIPO_CONTACTO' => htmlspecialchars($row['TIPO_CONTACTO'] ?? ''),
        'DIAS_RESPUESTA' => htmlspecialchars($row['DIAS_RESPUESTA'] ?? ''),
        'HORAS_RESPUESTA' => htmlspecialchars($row['HORAS_RESPUESTA'] ?? ''),
        'DIAS_CITA' => htmlspecialchars($row['DIAS_CITA'] ?? ''),
        'HORAS_CITA' => htmlspecialchars($row['HORAS_CITA'] ?? '')
    ];
}

$stmt_main->close();
$conn->close();

echo json_encode(['data' => $data_body]);
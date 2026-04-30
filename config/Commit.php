<?php
require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Metodo no permitido'], JSON_OUT);
    exit;
}

if (!is_session_valid()) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado'], JSON_OUT);
    exit;
}

$csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
if (!empty($csrf_token) && !validate_csrf_token($csrf_token)) {
    http_response_code(403);
    echo json_encode(['error' => 'Token CSRF invalido'], JSON_OUT);
    exit;
}

$data = [
    'pk_uuid' => isset($_POST['pk_uuid']) ? trim($_POST['pk_uuid']) : '',
    'dni' => isset($_POST['dni']) ? trim($_POST['dni']) : '',
    'documenttype' => isset($_POST['documenttype']) ? trim($_POST['documenttype']) : '',
    'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
    'lastname' => isset($_POST['lastname']) ? trim($_POST['lastname']) : '',
    'eps' => isset($_POST['Eps']) ? trim($_POST['Eps']) : '',
    'ips' => isset($_POST['ips']) ? trim($_POST['ips']) : '',
    'eps_classification' => isset($_POST['EpsClassification']) ? trim($_POST['EpsClassification']) : '',
    'diagnosis' => isset($_POST['diagnosis']) ? trim($_POST['diagnosis']) : '',
    'contacttype' => isset($_POST['contacttype']) ? trim($_POST['contacttype']) : '',
    'approved' => isset($_POST['approved']) ? intval($_POST['approved']) : 0,
    'observation_in' => isset($_POST['ObservationIn']) ? trim($_POST['ObservationIn']) : '',
    'sent_by' => isset($_POST['SentBy']) ? trim($_POST['SentBy']) : '',
    'eps_status' => isset($_POST['EpsStatus']) ? trim($_POST['EpsStatus']) : '',
    'call_number' => isset($_POST['CallNumber']) ? intval($_POST['CallNumber']) : 0,
    'exhibit_nine' => isset($_POST['exhibitNine']) ? intval($_POST['exhibitNine']) : 0,
    'exhibit_ten' => isset($_POST['exhibitTen']) ? intval($_POST['exhibitTen']) : 0,
    'send_to' => isset($_POST['sendTo']) ? trim($_POST['sendTo']) : '',
    'observation_out' => isset($_POST['ObservationOut']) ? trim($_POST['ObservationOut']) : '',
    'check_in_date' => isset($_POST['checkInDate']) ? trim($_POST['checkInDate']) : '',
    'comment_date' => isset($_POST['commentDate']) ? trim($_POST['commentDate']) : '',
    'attention_date' => isset($_POST['AtentionDate']) ? trim($_POST['AtentionDate']) : '',
];

$required_fields = [
    'dni'               => 'Identificacion',
    'name'              => 'Nombres',
    'lastname'          => 'Apellidos',
    'eps'               => 'EPS',
    'ips'               => 'IPS',
    'eps_classification'=> 'Rango EPS',
    'contacttype'       => 'Tipo de Contacto',
    'observation_in'    => 'Observacion (Referencia)',
    'sent_by'           => 'Remitido Desde',
    'eps_status'        => 'Estado EPS',
];
foreach ($required_fields as $field => $label) {
    if (!isset($data[$field]) || $data[$field] === '') {
        http_response_code(400);
        echo json_encode(['error' => "El campo \"$label\" es obligatorio."], JSON_OUT);
        exit;
    }
}

try {
    $check_in_datetime = !empty($data['check_in_date']) ? new DateTime($data['check_in_date']) : null;
    $comment_datetime = !empty($data['comment_date']) ? new DateTime($data['comment_date']) : null;
    $attention_datetime = !empty($data['attention_date']) ? new DateTime($data['attention_date']) : null;
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de fecha invalido'], JSON_OUT);
    exit;
}

$response_day_diff = 0;
$response_time_diff = '0:00';
$attention_day_diff = 0;
$attention_time_diff = '0:00';

if ($check_in_datetime && $comment_datetime) {
    $communication_diff = $comment_datetime->diff($check_in_datetime);
    $response_day_diff = $communication_diff->days;
    $response_time_diff = $communication_diff->h . ':' . str_pad($communication_diff->i, 2, '0', STR_PAD_LEFT);
}

if ($check_in_datetime && $attention_datetime && $data['approved'] == 1) {
    $attention_diff = $attention_datetime->diff($check_in_datetime);
    $attention_day_diff = $attention_diff->days;
    $attention_time_diff = $attention_diff->h . ':' . str_pad($attention_diff->i, 2, '0', STR_PAD_LEFT);
}

$username = $_SESSION['usuario'];

$conn->begin_transaction();

try {
    if (empty($data['pk_uuid'])) {
        $stmt_patient = $conn->prepare("INSERT INTO patients (id, document_number, document_type, first_name, last_name, eps_id, range_level, ips_id, created_by, updated_by) VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_patient->bind_param("sssssssss",
            $data['dni'],
            $data['documenttype'],
            $data['name'],
            $data['lastname'],
            $data['eps'],
            $data['eps_classification'],
            $data['ips'],
            $username,
            $username
        );
        if (!$stmt_patient->execute()) {
            throw new Exception("Error al guardar paciente: " . $stmt_patient->error);
        }
        $stmt_patient->close();

        $stmt_get = $conn->prepare("SELECT id FROM patients WHERE document_number = ?");
        $stmt_get->bind_param("s", $data['dni']);
        $stmt_get->execute();
        $result_get = $stmt_get->get_result();
        if ($result_get->num_rows > 0) {
            $row = $result_get->fetch_assoc();
            $data['pk_uuid'] = $row['id'];
        }
        $stmt_get->close();
    } else {
        $stmt_patient = $conn->prepare("UPDATE patients SET
            document_number = ?,
            document_type = ?,
            first_name = ?,
            last_name = ?,
            eps_id = ?,
            ips_id = ?,
            range_level = ?,
            updated_by = ?
            WHERE document_number = ?");
        $stmt_patient->bind_param("sssssssss",
            $data['dni'],
            $data['documenttype'],
            $data['name'],
            $data['lastname'],
            $data['eps'],
            $data['ips'],
            $data['eps_classification'],
            $username,
            $data['dni']
        );
        if (!$stmt_patient->execute()) {
            throw new Exception("Error al actualizar paciente: " . $stmt_patient->error);
        }
        $stmt_patient->close();
    }

    $checkin_date_str = $check_in_datetime ? $check_in_datetime->format('Y-m-d') : null;
    $checkin_time_str = $check_in_datetime ? $check_in_datetime->format('H:i:s') : null;
    $resp_date_str    = $comment_datetime  ? $comment_datetime->format('Y-m-d')  : null;
    $resp_time_str    = $comment_datetime  ? $comment_datetime->format('H:i:s')  : null;

    $sql_priority = "INSERT INTO priorities (
        id, patient_id, eps_id, ips_id, range_level, diagnosis_id, document_number, first_name,
        last_name, contact_type, approved, sent_by, eps_status, calls_count, reception_notes,
        created_by, updated_by, annex_nine, annex_ten, sent_to, outgoing_notes,
        checkin_date, checkin_time, response_date, response_time, response_day_diff, response_hour_diff
    ) VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $types = "sssssssssssssssssssssssss";
    $params = [
        $data['pk_uuid'], $data['eps'], $data['ips'], $data['eps_classification'],
        $data['diagnosis'], $data['dni'], $data['name'], $data['lastname'],
        $data['contacttype'], $data['approved'], $data['sent_by'], $data['eps_status'],
        $data['call_number'], $data['observation_in'], $username, $username,
        $data['exhibit_nine'], $data['exhibit_ten'], $data['send_to'], $data['observation_out'],
        $checkin_date_str, $checkin_time_str,
        $resp_date_str, $resp_time_str,
        $response_day_diff, $response_time_diff
    ];

    if ($data['approved'] == 1) {
        $sql_priority = "INSERT INTO priorities (
            id, patient_id, eps_id, ips_id, range_level, diagnosis_id, document_number, first_name,
            last_name, contact_type, approved, sent_by, eps_status, calls_count, reception_notes,
            created_by, updated_by, annex_nine, annex_ten, sent_to, outgoing_notes,
            checkin_date, checkin_time, response_date, response_time, response_day_diff, response_hour_diff,
            appointment_date, appointment_time, attention_day_diff, attention_hour_diff
        ) VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $types .= "ssss";
        $params[] = $attention_datetime ? $attention_datetime->format('Y-m-d') : null;
        $params[] = $attention_datetime ? $attention_datetime->format('H:i:s') : null;
        $params[] = $attention_day_diff;
        $params[] = $attention_time_diff;
    }

    $stmt_priority = $conn->prepare($sql_priority);
    if (!$stmt_priority) {
        throw new Exception("Error al preparar consulta de prioridad: " . $conn->error);
    }
    $stmt_priority->bind_param($types, ...$params);
    if (!$stmt_priority->execute()) {
        throw new Exception("Error al guardar prioridad: " . $stmt_priority->error);
    }

    $conn->commit();

    security_log('PRIORITY_CREATED', 'Nueva prioridad registrada para DNI: ' . $data['dni']);

    echo json_encode([
        'success' => true,
        'message' => 'Registro guardado exitosamente'
    ], JSON_OUT);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al guardar registro: ' . $e->getMessage()
    ], JSON_OUT);
}

if (isset($stmt_patient)) $stmt_patient->close();
if (isset($stmt_priority)) $stmt_priority->close();
$conn->close();

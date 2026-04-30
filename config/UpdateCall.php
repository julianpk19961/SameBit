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
    'call_id'           => isset($_POST['call_id'])           ? trim($_POST['call_id'])           : '',
    'name'              => isset($_POST['name'])               ? trim($_POST['name'])               : '',
    'lastname'          => isset($_POST['lastname'])           ? trim($_POST['lastname'])           : '',
    'eps'               => isset($_POST['Eps'])                ? trim($_POST['Eps'])                : '',
    'ips'               => isset($_POST['ips'])                ? trim($_POST['ips'])                : '',
    'eps_classification'=> isset($_POST['EpsClassification'])  ? trim($_POST['EpsClassification'])  : '',
    'diagnosis'         => isset($_POST['diagnosis'])          ? trim($_POST['diagnosis'])          : '',
    'contacttype'       => isset($_POST['contacttype'])        ? trim($_POST['contacttype'])        : '',
    'approved'          => isset($_POST['approved'])           ? intval($_POST['approved'])         : 0,
    'observation_in'    => isset($_POST['ObservationIn'])      ? trim($_POST['ObservationIn'])      : '',
    'sent_by'           => isset($_POST['SentBy'])             ? trim($_POST['SentBy'])             : '',
    'eps_status'        => isset($_POST['EpsStatus'])          ? trim($_POST['EpsStatus'])          : '',
    'call_number'       => isset($_POST['CallNumber'])         ? intval($_POST['CallNumber'])       : 0,
    'exhibit_nine'      => isset($_POST['exhibitNine'])        ? intval($_POST['exhibitNine'])      : 0,
    'exhibit_ten'       => isset($_POST['exhibitTen'])         ? intval($_POST['exhibitTen'])       : 0,
    'send_to'           => isset($_POST['sendTo'])             ? trim($_POST['sendTo'])             : '',
    'observation_out'   => isset($_POST['ObservationOut'])     ? trim($_POST['ObservationOut'])     : '',
    'check_in_date'     => isset($_POST['checkInDate'])        ? trim($_POST['checkInDate'])        : '',
    'comment_date'      => isset($_POST['commentDate'])        ? trim($_POST['commentDate'])        : '',
    'attention_date'    => isset($_POST['AtentionDate'])       ? trim($_POST['AtentionDate'])       : '',
];

if (empty($data['call_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de llamada requerido'], JSON_OUT);
    exit;
}

$required_fields = [
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
    $check_in_datetime  = !empty($data['check_in_date'])  ? new DateTime($data['check_in_date'])  : null;
    $comment_datetime   = !empty($data['comment_date'])   ? new DateTime($data['comment_date'])   : null;
    $attention_datetime = !empty($data['attention_date']) ? new DateTime($data['attention_date']) : null;
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de fecha invalido'], JSON_OUT);
    exit;
}

$response_day_diff  = 0;
$response_time_diff = '0:00';
$attention_day_diff = 0;
$attention_time_diff = '0:00';

if ($check_in_datetime && $comment_datetime) {
    $diff = $comment_datetime->diff($check_in_datetime);
    $response_day_diff  = $diff->days;
    $response_time_diff = $diff->h . ':' . str_pad($diff->i, 2, '0', STR_PAD_LEFT);
}

if ($check_in_datetime && $attention_datetime && $data['approved'] == 1) {
    $diff = $attention_datetime->diff($check_in_datetime);
    $attention_day_diff  = $diff->days;
    $attention_time_diff = $diff->h . ':' . str_pad($diff->i, 2, '0', STR_PAD_LEFT);
}

$username = $_SESSION['usuario'];

$stmt_get = $conn->prepare("SELECT patient_id, document_number FROM priorities WHERE id = ?");
$stmt_get->bind_param("s", $data['call_id']);
$stmt_get->execute();
$res = $stmt_get->get_result();
if ($res->num_rows === 0) {
    $stmt_get->close();
    http_response_code(404);
    echo json_encode(['error' => 'Llamada no encontrada'], JSON_OUT);
    exit;
}
$existing = $res->fetch_assoc();
$stmt_get->close();

$conn->begin_transaction();

try {
    $stmt_patient = $conn->prepare("
        UPDATE patients SET
            first_name  = ?,
            last_name   = ?,
            eps_id      = ?,
            ips_id      = ?,
            range_level = ?,
            updated_by  = ?
        WHERE id = ?
    ");
    $stmt_patient->bind_param("sssssss",
        $data['name'],
        $data['lastname'],
        $data['eps'],
        $data['ips'],
        $data['eps_classification'],
        $username,
        $existing['patient_id']
    );
    if (!$stmt_patient->execute()) {
        throw new Exception("Error al actualizar paciente: " . $stmt_patient->error);
    }
    $stmt_patient->close();

    $checkin_date_str = $check_in_datetime ? $check_in_datetime->format('Y-m-d') : null;
    $checkin_time_str = $check_in_datetime ? $check_in_datetime->format('H:i:s') : null;
    $resp_date_str    = $comment_datetime  ? $comment_datetime->format('Y-m-d')  : null;
    $resp_time_str    = $comment_datetime  ? $comment_datetime->format('H:i:s')  : null;

    $sql = "UPDATE priorities SET
        eps_id            = ?,
        ips_id            = ?,
        range_level       = ?,
        diagnosis_id      = ?,
        first_name        = ?,
        last_name         = ?,
        contact_type      = ?,
        approved          = ?,
        sent_by           = ?,
        eps_status        = ?,
        calls_count       = ?,
        reception_notes   = ?,
        annex_nine        = ?,
        annex_ten         = ?,
        sent_to           = ?,
        outgoing_notes    = ?,
        checkin_date      = ?,
        checkin_time      = ?,
        response_date     = ?,
        response_time     = ?,
        response_day_diff = ?,
        response_hour_diff= ?,
        updated_by        = ?";

    $types = "sssssssssssssssssssssss";
    $params = [
        $data['eps'],
        $data['ips'],
        $data['eps_classification'],
        $data['diagnosis'] ?: null,
        $data['name'],
        $data['lastname'],
        $data['contacttype'],
        $data['approved'],
        $data['sent_by'],
        $data['eps_status'],
        $data['call_number'],
        $data['observation_in'],
        $data['exhibit_nine'],
        $data['exhibit_ten'],
        $data['send_to'],
        $data['observation_out'],
        $checkin_date_str,
        $checkin_time_str,
        $resp_date_str,
        $resp_time_str,
        $response_day_diff,
        $response_time_diff,
        $username,
    ];

    if ($data['approved'] == 1) {
        $sql .= ", appointment_date    = ?,
                   appointment_time    = ?,
                   attention_day_diff  = ?,
                   attention_hour_diff = ?";
        $types .= "ssss";
        $params[] = $attention_datetime ? $attention_datetime->format('Y-m-d') : null;
        $params[] = $attention_datetime ? $attention_datetime->format('H:i:s') : null;
        $params[] = $attention_day_diff;
        $params[] = $attention_time_diff;
    }

    $sql .= " WHERE id = ?";
    $types .= "s";
    $params[] = $data['call_id'];

    $stmt_priority = $conn->prepare($sql);
    if (!$stmt_priority) {
        throw new Exception("Error al preparar consulta: " . $conn->error);
    }
    $stmt_priority->bind_param($types, ...$params);
    if (!$stmt_priority->execute()) {
        throw new Exception("Error al actualizar llamada: " . $stmt_priority->error);
    }
    $stmt_priority->close();

    $conn->commit();

    security_log('PRIORITY_UPDATED', 'Prioridad actualizada ID: ' . $data['call_id']);

    echo json_encode(['success' => true, 'message' => 'Registro actualizado exitosamente'], JSON_OUT);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Error al actualizar registro: ' . $e->getMessage()], JSON_OUT);
}

$conn->close();

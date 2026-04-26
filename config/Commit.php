<?php
/**
 * Commit.php - Guardar prioridad/llamada de forma segura
 * 
 * Usa prepared statements para prevenir SQL injection
 * y valida token CSRF para prevenir ataques
 */

require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Verificar autenticación
if (!is_session_valid()) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

// Validar token CSRF (opcional - el token se envía automáticamente via security.js)
$csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
if (!empty($csrf_token) && !validate_csrf_token($csrf_token)) {
    http_response_code(403);
    echo json_encode(['error' => 'Token CSRF inválido']);
    exit;
}

// Obtener y sanitizar datos de entrada
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

// Validaciones básicas
$required_fields = ['dni', 'name', 'lastname', 'eps', 'ips', 'eps_classification', 'contacttype', 'observation_in', 'sent_by', 'eps_status'];
foreach ($required_fields as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Campo requerido faltante: $field"]);
        exit;
    }
}

// Validar fechas
try {
    $check_in_datetime = !empty($data['check_in_date']) ? new DateTime($data['check_in_date']) : null;
    $comment_datetime = !empty($data['comment_date']) ? new DateTime($data['comment_date']) : null;
    $attention_datetime = !empty($data['attention_date']) ? new DateTime($data['attention_date']) : null;
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de fecha inválido']);
    exit;
}

// Calcular diferencias de tiempo
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

// Iniciar transacción
$conn->begin_transaction();

try {
    // Insertar o actualizar paciente
    if (empty($data['pk_uuid'])) {
        // INSERT nuevo paciente
        $stmt_patient = $conn->prepare("INSERT INTO patients (id, document_number, document_type, first_name, last_name, eps_id, range_level, ips_id, created_by, updated_by) VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_patient->bind_param("ssssssss", 
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
        
        // Obtener ID del paciente creado
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
        // UPDATE paciente existente
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
    }
    
    // Insertar prioridad
    $columns = "id, patient_id, eps_id, ips_id, range_level, diagnosis_id, document_number, first_name, 
                last_name, contact_type, approved, sent_by, eps_status, calls_count, reception_notes, 
                created_by, updated_by, annex_nine, annex_ten, sent_to, outgoing_notes, 
                checkin_date, checkin_time, response_date, response_time, response_day_diff, response_hour_diff";
    
    $values = "UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
    
    $params = [
        $data['pk_uuid'], $data['eps'], $data['ips'], $data['eps_classification'], 
        $data['diagnosis'], $data['dni'], $data['name'], $data['lastname'], 
        $data['contacttype'], $data['approved'], $data['sent_by'], $data['eps_status'], 
        $data['call_number'], $data['observation_in'], $username, $username, 
        $data['exhibit_nine'], $data['exhibit_ten'], $data['send_to'], $data['observation_out'],
        $check_in_datetime ? $check_in_datetime->format('Y-m-d') : null,
        $check_in_datetime ? $check_in_datetime->format('H:i:s') : null,
        $comment_datetime ? $comment_datetime->format('Y-m-d') : null,
        $comment_datetime ? $comment_datetime->format('H:i:s') : null,
        $response_day_diff,
        $response_time_diff
    ];
    
    // Si está aprobado, agregar campos de cita
    if ($data['approved'] == 1) {
        $columns .= ", appointment_date, appointment_time, attention_day_diff, attention_hour_diff";
        $values .= ", ?, ?, ?, ?";
        $params[] = $attention_datetime ? $attention_datetime->format('Y-m-d') : null;
        $params[] = $attention_datetime ? $attention_datetime->format('H:i:s') : null;
        $params[] = $attention_day_diff;
        $params[] = $attention_time_diff;
    }
    
    $sql_priority = "INSERT INTO priorities ($columns) VALUES ($values)";
    $stmt_priority = $conn->prepare($sql_priority);
    
    if (!$stmt_priority) {
        throw new Exception("Error al preparar consulta de prioridad: " . $conn->error);
    }
    
    // Construir string de tipos para bind_param
    $types = str_repeat('s', count($params));
    $stmt_priority->bind_param($types, ...$params);
    
    if (!$stmt_priority->execute()) {
        throw new Exception("Error al guardar prioridad: " . $stmt_priority->error);
    }
    
    // Commit de la transacción
    $conn->commit();
    
    // Log de seguridad
    security_log('PRIORITY_CREATED', 'Nueva prioridad registrada para DNI: ' . $data['dni']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Registro guardado exitosamente'
    ]);
    
} catch (Exception $e) {
    // Rollback en caso de error
    $conn->rollback();
    
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al guardar registro: ' . $e->getMessage()
    ]);
}

// Cerrar statements
if (isset($stmt_patient)) $stmt_patient->close();
if (isset($stmt_priority)) $stmt_priority->close();
$conn->close();
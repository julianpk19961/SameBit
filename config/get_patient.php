<?php
/**
 * Endpoint: Obtener un paciente por UUID
 * POST /config/get_patient.php
 * Body: { id: uuid }
 * Response: { success: true, data: { ... } }
 */

require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    require_auth();

    $id = trim($_POST['id'] ?? '');
    if (!$id) {
        throw new Exception('ID requerido');
    }

    // Try with extended columns first; fall back if migration not yet applied
    $stmt = $conn->prepare("
        SELECT id, document_type, document_number,
               first_name, last_name,
               COALESCE(gender, '')     AS gender,
               COALESCE(birth_date, '') AS birth_date,
               COALESCE(phone, '')      AS phone,
               COALESCE(mobile, '')     AS mobile,
               COALESCE(email, '')      AS email,
               COALESCE(address, '')    AS address,
               eps_id, ips_id, range_level,
               COALESCE(active, 1)      AS active
        FROM patients
        WHERE id = ?
        LIMIT 1
    ");

    if (!$stmt) {
        // Columns may not exist yet — use base schema only
        $stmt = $conn->prepare("
            SELECT id, document_type, document_number,
                   first_name, last_name,
                   '' AS gender, '' AS birth_date,
                   '' AS phone,  '' AS mobile,
                   '' AS email,  '' AS address,
                   eps_id, ips_id, range_level,
                   1  AS active
            FROM patients
            WHERE id = ?
            LIMIT 1
        ");
        if (!$stmt) {
            throw new Exception('Error preparando consulta: ' . $conn->error);
        }
    }

    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row    = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'Paciente no encontrado']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $row], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

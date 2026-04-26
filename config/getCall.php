<?php
require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

if (!is_session_valid()) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado'], JSON_OUT);
    exit;
}

$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if (empty($id)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID requerido'], JSON_OUT);
    exit;
}

$stmt = $conn->prepare("
    SELECT
        pr.id                   AS call_id,
        pr.patient_id,
        pr.eps_id,
        pr.ips_id,
        pr.range_level,
        pr.diagnosis_id,
        pr.document_number,
        pr.first_name,
        pr.last_name,
        pr.contact_type,
        pr.approved,
        pr.sent_by,
        pr.eps_status,
        pr.calls_count,
        pr.reception_notes,
        pr.annex_nine,
        pr.annex_ten,
        pr.sent_to,
        pr.outgoing_notes,
        pr.checkin_date,
        pr.checkin_time,
        pr.response_date,
        pr.response_time,
        pr.appointment_date,
        pr.appointment_time,
        p.document_type,
        d.id          AS diag_id,
        d.code        AS diag_code,
        d.description AS diag_desc
    FROM priorities pr
    LEFT JOIN patients  p ON pr.patient_id   = p.id
    LEFT JOIN diagnoses d ON pr.diagnosis_id = d.id
    WHERE pr.id = ?
");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno'], JSON_OUT);
    exit;
}

$stmt->execute([$id]);
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Registro no encontrado'], JSON_OUT);
    exit;
}

echo json_encode($result->fetch_assoc(), JSON_OUT);
$stmt->close();
$conn->close();

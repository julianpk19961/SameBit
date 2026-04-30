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

$dni = isset($_POST['dni']) ? trim($_POST['dni']) : '';

if (empty($dni)) {
    http_response_code(400);
    echo json_encode(['error' => 'DNI requerido'], JSON_OUT);
    exit;
}

if (!ctype_alnum($dni)) {
    http_response_code(400);
    echo json_encode(['error' => 'DNI invalido'], JSON_OUT);
    exit;
}

$stmt = $conn->prepare("
    SELECT b.response_date, b.response_time, b.created_by, b.reception_notes
    FROM priorities b
    INNER JOIN patients p ON p.id = b.patient_id
    WHERE p.document_number = ?
    ORDER BY b.response_date DESC, b.response_time DESC
    LIMIT 200
");
$stmt->bind_param("s", $dni);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $json = [];
    while ($row = $result->fetch_assoc()) {
        $json[] = [
            'commentdate' => $row['response_date'],
            'commenttime' => $row['response_time'],
            'createdUser' => $row['created_by'],
            'comment0'    => $row['reception_notes']
        ];
    }
    echo json_encode($json, JSON_OUT);
} else {
    echo json_encode([], JSON_OUT);
}

$stmt->close();
$conn->close();

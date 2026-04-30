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

$pk_uuid = isset($_POST['pk_uuid']) ? trim($_POST['pk_uuid']) : '';

if (empty($pk_uuid)) {
    http_response_code(400);
    echo json_encode(['error' => 'UUID requerido'], JSON_OUT);
    exit;
}

if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $pk_uuid)) {
    http_response_code(400);
    echo json_encode(['error' => 'UUID invalido'], JSON_OUT);
    exit;
}

$stmt = $conn->prepare("
    SELECT k.final_quantity
    FROM kardex AS k
    INNER JOIN movement_categories AS mc ON mc.id = k.category_id
    WHERE k.medicine_id = ?
    ORDER BY k.movement_date DESC
    LIMIT 1
");
$stmt->bind_param("s", $pk_uuid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $json = [];
    while ($row = $result->fetch_assoc()) {
        $json[] = ['finalQuantity' => $row['final_quantity']];
    }
    echo json_encode($json, JSON_OUT);
} else {
    echo json_encode([], JSON_OUT);
}

$stmt->close();
$conn->close();

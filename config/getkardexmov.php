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

$responseArray = [];

$sql_categories = "SELECT id, abbreviation, name FROM movement_categories";
$result = $conn->query($sql_categories);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en consulta'], JSON_OUT);
    exit;
}

$jsoncategories = [];
while ($row = $result->fetch_assoc()) {
    $jsoncategories[] = [
        'KP_UUID' => $row['id'],
        'abbr'    => $row['abbreviation'],
        'name'    => $row['name']
    ];
}
$responseArray[] = $jsoncategories;

$stmt = $conn->prepare("
    SELECT k.movement_date, mc.name AS category, k.patient_id AS patient, k.type,
           k.quantity, k.final_quantity, k.bill, k.notes AS comment
    FROM kardex AS k
    INNER JOIN movement_categories AS mc ON mc.id = k.category_id
    WHERE k.medicine_id = ?
    ORDER BY k.movement_date DESC
    LIMIT 500
");
$stmt->bind_param("s", $pk_uuid);
$stmt->execute();
$result = $stmt->get_result();

$jsonkardex = [];
while ($row = $result->fetch_assoc()) {
    $jsonkardex[] = [
        'zCrea'         => $row['movement_date'],
        'category'      => $row['category'],
        'patient'       => $row['patient'],
        'bill'          => $row['bill'],
        'type'          => $row['type'],
        'quantity'      => $row['quantity'],
        'finalQuantity' => $row['final_quantity'],
        'comment'       => $row['comment'],
    ];
}
$responseArray[] = $jsonkardex;

$stmt->close();
echo json_encode($responseArray, JSON_OUT);
$conn->close();

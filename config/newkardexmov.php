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

$medicine = isset($_POST['pk_uuid'])  ? trim($_POST['pk_uuid'])  : '';
$date     = isset($_POST['date'])     ? trim($_POST['date'])     : '';
$patient  = isset($_POST['patient'])  ? trim($_POST['patient'])  : '';
$bill     = isset($_POST['bill'])     ? trim($_POST['bill'])     : '';
$category = isset($_POST['category']) ? trim($_POST['category']) : '';
$quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';
$comment  = isset($_POST['comment'])  ? trim($_POST['comment'])  : '';

if (empty($medicine) || empty($category) || $quantity === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Campos requeridos: medicamento, categoria, cantidad'], JSON_OUT);
    exit;
}

if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $medicine)) {
    http_response_code(400);
    echo json_encode(['error' => 'UUID de medicamento invalido'], JSON_OUT);
    exit;
}

if (!is_numeric($quantity)) {
    http_response_code(400);
    echo json_encode(['error' => 'La cantidad debe ser numerica'], JSON_OUT);
    exit;
}

$quantity = floatval($quantity);

if (!empty($date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de fecha invalido'], JSON_OUT);
    exit;
}

$stmt = $conn->prepare("SELECT MAX(movement_date) AS last_date FROM kardex WHERE medicine_id = ?");
$stmt->bind_param("s", $medicine);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

$lastTimeStamp = empty($row['last_date']) ? 0 : $row['last_date'];
$lastDate_date = $lastTimeStamp ? explode(' ', $lastTimeStamp)[0] : '';

if ($lastDate_date && $lastDate_date > $date) {
    http_response_code(400);
    echo json_encode(['error' => 'No puede ingresar fechas inferiores a la ultima registrada'], JSON_OUT);
    exit;
}

if ($lastDate_date && $lastDate_date <= $date && $date < date('Y-m-d')) {
    $lastDate_time = strtotime(explode(' ', $lastTimeStamp)[1]);
    $hour       = date('H', $lastDate_time);
    $LastDay    = date('d', strtotime($lastTimeStamp));
    $currentDay = date('d', strtotime($date));

    if ($LastDay < $currentDay) {
        $new_time = strtotime('23:00:00');
    } else {
        $new_time = strtotime($hour < 23 ? '23:00:00' : '+ 1 seconds', $lastDate_time);
    }
    $date = $date . ' ' . date('H:i:s', $new_time);
} else {
    $date = date('Y-m-d H:i:s');
}

$stmt = $conn->prepare("SELECT final_quantity FROM kardex WHERE medicine_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("s", $medicine);
$stmt->execute();
$result = $stmt->get_result();
$saldoRow = $result->fetch_assoc();
$stmt->close();

$saldoinicial = $saldoRow ? floatval($saldoRow['final_quantity']) : 0;

$stmt = $conn->prepare("SELECT type FROM movement_categories WHERE id = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    http_response_code(400);
    echo json_encode(['error' => 'Categoria no encontrada'], JSON_OUT);
    exit;
}

$typeRow = $result->fetch_assoc();
$stmt->close();
$type = intval($typeRow['type']);

if ($type != 2) {
    $operator = ($type == 1) ? 1 : -1;
    $total = $saldoinicial + ($operator * $quantity);

    if ($total < 0) {
        http_response_code(400);
        echo json_encode(['error' => 'La cantidad supera la existencia en el kardex'], JSON_OUT);
        exit;
    }
} else {
    $total   = $quantity;
    $patient = 'bit-medical - VISITA';
}

$stmt = $conn->prepare("INSERT INTO kardex (medicine_id, patient_id, category_id, type, initial_quantity, quantity, final_quantity, bill, movement_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssidddsss", $medicine, $patient, $category, $type, $saldoinicial, $quantity, $total, $bill, $date, $comment);

if ($stmt->execute()) {
    security_log('KARDEX_MOVEMENT', 'Movimiento kardex creado - Medicamento: ' . $medicine);
    echo json_encode(['success' => true, 'id' => $medicine], JSON_OUT);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar movimiento'], JSON_OUT);
}

$stmt->close();
$conn->close();

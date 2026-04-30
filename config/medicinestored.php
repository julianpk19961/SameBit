<?php
/**
 * medicinestored.php - Guardar medicamento de forma segura
 * 
 * Usa prepared statements para prevenir SQL injection
 * y valida los datos de entrada
 */

require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido'], JSON_OUT);
    exit;
}

// Obtener y sanitizar datos de entrada
$name = isset($_POST["name"]) ? trim($_POST["name"]) : '';
$reference = isset($_POST["reference"]) ? trim($_POST["reference"]) : '';
$notes = isset($_POST["observation"]) ? trim($_POST["observation"]) : '';

// Validaciones
if (empty($name)) {
    http_response_code(400);
    echo json_encode(['error' => 'El nombre del medicamento es requerido'], JSON_OUT);
    exit;
}

// Validar longitud del nombre
if (strlen($name) > 200) {
    http_response_code(400);
    echo json_encode(['error' => 'El nombre del medicamento es demasiado largo (max 200 caracteres)'], JSON_OUT);
    exit;
}

// Validar longitud de referencia
if (strlen($reference) > 100) {
    http_response_code(400);
    echo json_encode(['error' => 'La referencia es demasiado larga (max 100 caracteres)'], JSON_OUT);
    exit;
}

if (!function_exists('com_create_guid')) {
    function com_create_guid() {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

$new_uuid = com_create_guid();

// Usar prepared statement para INSERT
$stmt = $conn->prepare("INSERT INTO medicines (id, name, reference, notes) VALUES (?, ?, ?, ?)");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->error], JSON_OUT);
    exit;
}

$stmt->bind_param("ssss", $new_uuid, $name, $reference, $notes);

if ($stmt->execute()) {
    // Obtener el registro creado con prepared statement
    $select_stmt = $conn->prepare("SELECT id, name, reference, notes, active, created_at FROM medicines WHERE id = ?");
    $select_stmt->bind_param("s", $new_uuid);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $json = [
            'success' => true,
            'message' => 'Medicamento guardado exitosamente',
            'data' => [
                'id' => $row['id'],
                'name' => htmlspecialchars($row['name']),
                'reference' => htmlspecialchars($row['reference']),
                'notes' => htmlspecialchars($row['notes']),
                'active' => intval($row['active']),
                'created_at' => $row['created_at']
            ]
        ];
        echo json_encode($json, JSON_OUT);
    } else {
        echo json_encode(['success' => true, 'message' => 'Medicamento guardado, pero no se pudo recuperar'], JSON_OUT);
    }
    
    $select_stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar medicamento: ' . $conn->error], JSON_OUT);
}

$stmt->close();
$conn->close();

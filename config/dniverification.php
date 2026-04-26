<?php
/**
 * dniverification.php - Búsqueda segura de pacientes por DNI
 * 
 * Usa prepared statements para prevenir SQL injection
 * y retorna resultados en formato JSON
 */

require_once 'config.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido'], JSON_OUT);
    exit;
}

// Obtener y sanitizar DNI
$dni = isset($_POST['dni']) ? trim($_POST['dni']) : '';

// Validar que el DNI no esté vacío
if (empty($dni)) {
    echo json_encode(['error' => 'DNI requerido'], JSON_OUT);
    exit;
}

// Validar que el DNI sea numérico (ajustar según tipo de documento)
// Si se permiten letras en el DNI, quitar esta validación
if (!ctype_alnum($dni)) {
    echo json_encode(['error' => 'DNI inválido'], JSON_OUT);
    exit;
}

// Usar prepared statement para prevenir SQL injection
$stmt = $conn->prepare("SELECT id, first_name, last_name, document_number, document_type, eps_id, range_level 
                        FROM patients 
                        WHERE document_number LIKE ? 
                        ORDER BY document_number DESC 
                        LIMIT 20");

if (!$stmt) {
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->error], JSON_OUT);
    exit;
}

// El DNI se busca como prefijo (LIKE 'dni%')
$search_term = $dni . '%';
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    $stmt->close();
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->error], JSON_OUT);
    exit;
}

$result_count = $result->num_rows;

if ($result_count > 0) {
    $json = array();
    while ($row = $result->fetch_assoc()) {
        $json[] = array(
            'PK_UUID'      => htmlspecialchars($row['id']),
            'Name'         => htmlspecialchars($row['first_name']),
            'LastName'     => htmlspecialchars($row['last_name']),
            'dni'          => htmlspecialchars($row['document_number']),
            'documentType' => htmlspecialchars($row['document_type']),
            'eps'          => htmlspecialchars($row['eps_id']),
            'range'        => htmlspecialchars($row['range_level'])
        );
    }
    
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($json, JSON_OUT);
} else {
    echo 'error';
}

$stmt->close();
$conn->close();

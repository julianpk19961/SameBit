<?php
/**
 * usepatient.php - Obtener/Crear paciente de forma segura
 * 
 * Usa prepared statements para prevenir SQL injection
 * y retorna resultados en formato JSON
 */

require_once 'config.php';

header('Content-Type: application/json; charset=UTF-8');

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido'], JSON_OUT);
    exit;
}

// Si se proporciona pk_uuid, obtener datos del paciente
if (isset($_POST['pk_uuid']) && !empty($_POST['pk_uuid'])) {
    $pk_uuid = trim($_POST['pk_uuid']);
    
    // Validar que sea un UUID válido (formato básico)
    if (!preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $pk_uuid)) {
        echo json_encode(['error' => 'UUID inválido'], JSON_OUT);
        exit;
    }
    
    // Usar prepared statement para prevenir SQL injection
    $stmt = $conn->prepare("SELECT id, first_name, last_name, document_number, document_type, eps_id, range_level, ips_id 
                            FROM patients 
                            WHERE id = ?");
    
    if (!$stmt) {
        echo json_encode(['error' => 'Error en la consulta: ' . $conn->error], JSON_OUT);
        exit;
    }
    
    $stmt->bind_param("s", $pk_uuid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result) {
        $stmt->close();
        echo json_encode(['error' => 'Error en la consulta: ' . $conn->error], JSON_OUT);
        exit;
    }
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $json = array(
            'pk_uuid'      => htmlspecialchars($row['id']),
            'name'         => htmlspecialchars($row['first_name']),
            'lastname'     => htmlspecialchars($row['last_name']),
            'dni'          => htmlspecialchars($row['document_number']),
            'documentType' => htmlspecialchars($row['document_type']),
            'eps'          => htmlspecialchars($row['eps_id']),
            'range'        => htmlspecialchars($row['range_level']),
            'ips'          => htmlspecialchars($row['ips_id']),
        );
        echo json_encode($json, JSON_OUT);
    } else {
        echo json_encode(['error' => 'Paciente no encontrado'], JSON_OUT);
    }
    
    $stmt->close();
    $conn->close();
}

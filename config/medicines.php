<?php
/**
 * medicines.php - Listar medicamentos de forma segura
 * 
 * Retorna todos los medicamentos con su conteo de movimientos en kardex
 * Usa prepared statements y sanitización de salida
 */

require_once 'config.php';

header('Content-Type: application/json; charset=UTF-8');

// Consulta segura (no usa input de usuario, pero usamos buenas prácticas)
$sql = "SELECT 
            m.id,
            m.name,
            m.reference,
            m.notes,
            m.active,
            m.created_at,
            COUNT(k.id) AS nrows
        FROM medicines m
        LEFT JOIN kardex k ON k.medicine_id = m.id
        GROUP BY m.id, m.name, m.reference, m.notes, m.active, m.created_at
        ORDER BY m.name ASC";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->error], JSON_OUT);
    exit;
}

$result_count = $result->num_rows;

if ($result_count > 0) {
    $json = array();
    while ($row = $result->fetch_assoc()) {
        $json[] = array(
            'KP_UUID'    => htmlspecialchars($row['id']),
            'nombre'     => htmlspecialchars($row['name']),
            'referencia' => htmlspecialchars($row['reference']),
            'observacion'=> htmlspecialchars($row['notes']),
            'z_xOne'     => intval($row['active']),
            'nrows'      => intval($row['nrows'])
        );
    }
    echo json_encode($json, JSON_OUT);
} else {
    echo json_encode(['error' => 'No hay medicamentos registrados'], JSON_OUT);
}

$conn->close();

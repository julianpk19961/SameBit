<?php
/**
 * Endpoint: Obtener datos de usuario
 * POST /config/get_user.php
 */

require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

try {
    // Verificar que sea admin
    if ($_SESSION['privilege'] !== 'admin') {
        throw new Exception('Acceso denegado');
    }

    $user_id = $_POST['user_id'] ?? null;
    if (!$user_id) {
        throw new Exception('ID de usuario requerido');
    }

    // Obtener datos del usuario
    $stmt = $conn->prepare("SELECT id, username, first_name, last_name, profile_id, active FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Error en la consulta');
    }
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Usuario no encontrado');
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    echo json_encode([
        'success' => true,
        'data' => [
            'id' => $user['id'],
            'username' => htmlspecialchars($user['username']),
            'first_name' => htmlspecialchars($user['first_name']),
            'last_name' => htmlspecialchars($user['last_name']),
            'profile_id' => $user['profile_id'],
            'active' => $user['active']
        ]
    ], JSON_OUT);

} catch (Exception $e) {
    error_log("Error en get_user.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_OUT);
}
?>

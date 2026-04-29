<?php
/**
 * Endpoint: Eliminar usuario
 * POST /config/delete_user.php
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

    // No permitir que el admin se elimine a sí mismo
    if ($user_id === $_SESSION['id']) {
        throw new Exception('No puedes eliminar tu propia cuenta');
    }

    // Verificar que el usuario exista
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
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

    // Eliminar usuario
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Error preparando eliminación');
    }
    $stmt->bind_param("s", $user_id);
    if (!$stmt->execute()) {
        throw new Exception('Error al eliminar usuario: ' . $stmt->error);
    }
    $stmt->close();

    error_log("Usuario eliminado: {$user['username']}");

    echo json_encode([
        'success' => true,
        'message' => 'Usuario eliminado exitosamente'
    ], JSON_OUT);

} catch (Exception $e) {
    error_log("Error en delete_user.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_OUT);
}
?>

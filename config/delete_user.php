<?php
/**
 * Endpoint: Eliminar usuario
 * POST /config/delete_user.php
 */

require_once 'setup.php';
require_once 'PermissionManager.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

try {
    $pm = new PermissionManager($pdo, $_SESSION['user_id']);
    if (!$pm->isAdmin()) {
        throw new Exception('Acceso denegado');
    }

    $user_id = $_POST['user_id'] ?? null;
    if (!$user_id) {
        throw new Exception('ID de usuario requerido');
    }

    // Prevenir eliminar al usuario actual
    if ($user_id === $_SESSION['user_id']) {
        throw new Exception('No puedes eliminar tu propio usuario');
    }

    // Verificar que el usuario existe
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        throw new Exception('Usuario no encontrado');
    }

    $username = $result['username'];

    // Eliminar usuario (soft delete - actualizar active a 0)
    $stmt = $pdo->prepare("UPDATE users SET active = 0, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$user_id]);

    error_log("Usuario eliminado: $username (ID: $user_id)");

    echo json_encode([
        'success' => true,
        'message' => 'Usuario eliminado exitosamente'
    ]);

} catch (Exception $e) {
    error_log("Error en delete_user.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

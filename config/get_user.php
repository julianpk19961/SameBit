<?php
/**
 * Endpoint: Obtener datos de un usuario
 * POST /config/get_user.php
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

    $stmt = $pdo->prepare("
        SELECT id, username, first_name, last_name, profile_id, active
        FROM users
        WHERE id = ?
    ");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('Usuario no encontrado');
    }

    echo json_encode([
        'success' => true,
        'data' => $user
    ]);

} catch (Exception $e) {
    error_log("Error en get_user.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

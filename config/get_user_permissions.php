<?php
/**
 * Endpoint: Obtener permisos de un usuario
 * POST /config/get_user_permissions.php
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

    // Crear PermissionManager para el usuario solicitado
    $user_pm = new PermissionManager($pdo, $user_id);
    
    $profile = $user_pm->getUserProfile();
    if (!$profile) {
        throw new Exception('Usuario no encontrado');
    }

    $permissions = $user_pm->getUserPermissions();

    echo json_encode([
        'success' => true,
        'profile' => $profile,
        'permissions' => $permissions
    ]);

} catch (Exception $e) {
    error_log("Error en get_user_permissions.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

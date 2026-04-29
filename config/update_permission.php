<?php
/**
 * Endpoint AJAX para actualizar permisos de perfiles
 * POST /config/update_permission.php
 */

require_once 'setup.php';
require_once 'PermissionManager.php';

// Validar que sea POST AJAX
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

try {
    // Verificar que el usuario es admin
    $pm = new PermissionManager($pdo, $_SESSION['user_id']);
    if (!$pm->isAdmin()) {
        http_response_code(403);
        die(json_encode(['success' => false, 'message' => 'Acceso denegado']));
    }

    // Validar parámetros
    $profile_id = $_POST['profile_id'] ?? null;
    $module_slug = $_POST['module_slug'] ?? null;
    $permission_slug = $_POST['permission_slug'] ?? null;
    $can_access = (int)($_POST['can_access'] ?? 0);

    if (!$profile_id || !$module_slug || !$permission_slug) {
        throw new Exception('Parámetros incompletos');
    }

    // Validar que el perfil existe
    $stmt = $pdo->prepare("SELECT id FROM profiles WHERE id = ? AND active = 1");
    $stmt->execute([$profile_id]);
    if (!$stmt->fetch()) {
        throw new Exception('Perfil no encontrado');
    }

    // Obtener module_permission_id
    $stmt = $pdo->prepare("
        SELECT mp.id
        FROM module_permissions mp
        INNER JOIN modules m ON mp.module_id = m.id
        INNER JOIN permissions p ON mp.permission_id = p.id
        WHERE m.slug = ? AND p.slug = ? AND m.active = 1
    ");
    $stmt->execute([$module_slug, $permission_slug]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        throw new Exception('Módulo o permiso no encontrado');
    }

    $module_permission_id = $result['id'];

    // Actualizar o insertar permiso
    $stmt = $pdo->prepare("
        INSERT INTO profile_permissions (profile_id, module_permission_id, can_access)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE can_access = ?, updated_at = NOW()
    ");
    $stmt->execute([$profile_id, $module_permission_id, $can_access, $can_access]);

    // Log de auditoría (opcional - si existe tabla audit_log)
    error_log("Permiso actualizado: Perfil=$profile_id, Módulo=$module_slug, Permiso=$permission_slug, Acceso=$can_access");

    echo json_encode([
        'success' => true,
        'message' => 'Permiso actualizado exitosamente'
    ]);

} catch (Exception $e) {
    error_log("Error en update_permission.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

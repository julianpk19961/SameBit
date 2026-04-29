<?php
/**
 * Endpoint: Obtener permisos de usuario
 * POST /config/get_user_permissions.php
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

    // Obtener perfil del usuario
    $stmt = $conn->prepare("SELECT u.profile_id, p.name, p.slug FROM users u JOIN profiles p ON u.profile_id = p.id WHERE u.id = ?");
    if (!$stmt) {
        throw new Exception('Error en la consulta');
    }
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Usuario no encontrado');
    }

    $profile = $result->fetch_assoc();
    $stmt->close();

    // Obtener permisos del perfil
    $query = "
        SELECT m.name as module_name, p.name as permission_name, pp.can_access
        FROM profile_permissions pp
        JOIN module_permissions mp ON pp.module_permission_id = mp.id
        JOIN modules m ON mp.module_id = m.id
        JOIN permissions p ON mp.permission_id = p.id
        WHERE pp.profile_id = ?
        ORDER BY m.name, p.name
    ";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Error en la consulta de permisos');
    }
    $stmt->bind_param("s", $profile['profile_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $permissions = [];
    while ($row = $result->fetch_assoc()) {
        $module = $row['module_name'];
        if (!isset($permissions[$module])) {
            $permissions[$module] = [];
        }
        $permissions[$module][$row['permission_name']] = (bool)$row['can_access'];
    }
    $stmt->close();

    echo json_encode([
        'success' => true,
        'profile' => [
            'id' => $profile['profile_id'],
            'name' => htmlspecialchars($profile['name']),
            'slug' => htmlspecialchars($profile['slug'])
        ],
        'permissions' => $permissions
    ], JSON_OUT);

} catch (Exception $e) {
    error_log("Error en get_user_permissions.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_OUT);
}
?>

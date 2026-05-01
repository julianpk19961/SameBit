<?php
require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(400);
    die(json_encode(['success' => false], JSON_OUT));
}

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'No autenticado'], JSON_OUT));
}

try {
    $user_id = $_SESSION['id'];

    $query = "
        SELECT m.slug as module, p.slug as permission, pp.can_access
        FROM profile_permissions pp
        INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
        INNER JOIN modules m ON mp.module_id = m.id
        INNER JOIN permissions p ON mp.permission_id = p.id
        INNER JOIN users u ON u.profile_id = pp.profile_id
        WHERE u.id = ? AND m.active = 1 AND u.active = 1
        ORDER BY m.slug, p.slug
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $perms = [];
    while ($row = $result->fetch_assoc()) {
        if (!isset($perms[$row['module']])) {
            $perms[$row['module']] = [];
        }
        $perms[$row['module']][$row['permission']] = (bool)$row['can_access'];
    }
    $stmt->close();

    $profileQuery = "
        SELECT p.slug FROM users u
        INNER JOIN profiles p ON u.profile_id = p.id
        WHERE u.id = ?
    ";
    $stmt = $conn->prepare($profileQuery);
    $stmt->bind_param('s', $user_id);
    $stmt->execute();
    $profResult = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    echo json_encode([
        'success' => true,
        'profile' => $profResult ? $profResult['slug'] : null,
        'permissions' => $perms
    ], JSON_OUT);

} catch (Exception $e) {
    error_log("Error en my_permissions.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_OUT);
}

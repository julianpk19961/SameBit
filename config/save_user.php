<?php
/**
 * Endpoint: Guardar/Crear usuario
 * POST /config/save_user.php
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

    // Validar campos
    $user_id = $_POST['user_id'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $first_name = $_POST['first_name'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $profile_id = $_POST['profile_id'] ?? null;
    $active = (int)($_POST['active'] ?? 1);

    if (!$username || !$first_name || !$last_name || !$profile_id) {
        throw new Exception('Campos requeridos incompletos');
    }

    // Validar perfil
    $stmt = $pdo->prepare("SELECT id FROM profiles WHERE id = ? AND active = 1");
    $stmt->execute([$profile_id]);
    if (!$stmt->fetch()) {
        throw new Exception('Perfil no válido');
    }

    // Crear nuevo usuario
    if (empty($user_id)) {
        if (!$password || strlen($password) < 6) {
            throw new Exception('Contraseña requerida y mínimo 6 caracteres');
        }

        // Verificar que el username sea único
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            throw new Exception('El usuario ya existe');
        }

        $stmt = $pdo->prepare("
            INSERT INTO users (id, username, password, first_name, last_name, profile_id, active)
            VALUES (UUID(), ?, MD5(?), ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $password, $first_name, $last_name, $profile_id, $active]);

        error_log("Usuario creado: $username");

        echo json_encode([
            'success' => true,
            'message' => 'Usuario creado exitosamente'
        ]);
    } else {
        // Actualizar usuario existente
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        if (!$stmt->fetch()) {
            throw new Exception('Usuario no encontrado');
        }

        // Preparar query de actualización
        if (!empty($password)) {
            if (strlen($password) < 6) {
                throw new Exception('Contraseña debe tener mínimo 6 caracteres');
            }
            $stmt = $pdo->prepare("
                UPDATE users
                SET username = ?, password = MD5(?), first_name = ?, last_name = ?, profile_id = ?, active = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$username, $password, $first_name, $last_name, $profile_id, $active, $user_id]);
        } else {
            $stmt = $pdo->prepare("
                UPDATE users
                SET username = ?, first_name = ?, last_name = ?, profile_id = ?, active = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$username, $first_name, $last_name, $profile_id, $active, $user_id]);
        }

        error_log("Usuario actualizado: $username");

        echo json_encode([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente'
        ]);
    }

} catch (Exception $e) {
    error_log("Error en save_user.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

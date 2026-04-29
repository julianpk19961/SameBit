<?php
/**
 * Endpoint: Guardar/Crear usuario
 * POST /config/save_user.php
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
        throw new Exception('Acceso denegado - solo administradores');
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
    $stmt = $conn->prepare("SELECT id FROM profiles WHERE id = ? AND active = 1");
    if (!$stmt) {
        throw new Exception('Error en la consulta de perfil');
    }
    $stmt->bind_param("s", $profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception('Perfil no válido');
    }
    $stmt->close();

    // Crear nuevo usuario
    if (empty($user_id)) {
        if (!$password || strlen($password) < 6) {
            throw new Exception('Contraseña requerida y mínimo 6 caracteres');
        }

        // Verificar que el username sea único
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        if (!$stmt) {
            throw new Exception('Error en la consulta de usuario');
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            throw new Exception('El usuario ya existe');
        }
        $stmt->close();

        // Crear usuario con UUID
        $user_id = bin2hex(random_bytes(18)); // Simular UUID
        $password_hash = md5($password);

        $stmt = $conn->prepare("
            INSERT INTO users (id, username, password, first_name, last_name, profile_id, active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        if (!$stmt) {
            throw new Exception('Error preparando inserción');
        }
        $stmt->bind_param("ssssssi", $user_id, $username, $password_hash, $first_name, $last_name, $profile_id, $active);
        if (!$stmt->execute()) {
            throw new Exception('Error al crear usuario: ' . $stmt->error);
        }
        $stmt->close();

        error_log("Usuario creado: $username");

        echo json_encode([
            'success' => true,
            'message' => 'Usuario creado exitosamente'
        ], JSON_OUT);
    } else {
        // Actualizar usuario existente
        $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
        if (!$stmt) {
            throw new Exception('Error en la consulta de usuario');
        }
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception('Usuario no encontrado');
        }
        $stmt->close();

        // Preparar query de actualización
        if (!empty($password)) {
            if (strlen($password) < 6) {
                throw new Exception('Contraseña debe tener mínimo 6 caracteres');
            }
            $password_hash = md5($password);
            $stmt = $conn->prepare("
                UPDATE users
                SET username = ?, password = ?, first_name = ?, last_name = ?, profile_id = ?, active = ?, updated_at = NOW()
                WHERE id = ?
            ");
            if (!$stmt) {
                throw new Exception('Error preparando actualización');
            }
            $stmt->bind_param("sssssii", $username, $password_hash, $first_name, $last_name, $profile_id, $active, $user_id);
            if (!$stmt->execute()) {
                throw new Exception('Error al actualizar usuario: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("
                UPDATE users
                SET username = ?, first_name = ?, last_name = ?, profile_id = ?, active = ?, updated_at = NOW()
                WHERE id = ?
            ");
            if (!$stmt) {
                throw new Exception('Error preparando actualización');
            }
            $stmt->bind_param("ssssii", $username, $first_name, $last_name, $profile_id, $active, $user_id);
            if (!$stmt->execute()) {
                throw new Exception('Error al actualizar usuario: ' . $stmt->error);
            }
            $stmt->close();
        }

        error_log("Usuario actualizado: $username");

        echo json_encode([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente'
        ], JSON_OUT);
    }

} catch (Exception $e) {
    error_log("Error en save_user.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_OUT);
}
?>

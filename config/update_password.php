<?php
/**
 * update_password.php - Actualización segura de contraseñas
 * 
 * Este archivo permite actualizar la contraseña de un usuario
 * usando bcrypt para el hash.
 * 
 * Uso: AJAX POST con los siguientes parámetros:
 * - user_id: ID del usuario (opcional, si no se proporciona usa el usuario actual)
 * - new_password: Nueva contraseña
 * - confirm_password: Confirmación de contraseña
 * - csrf_token: Token CSRF
 */

require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido'], JSON_OUT);
    exit;
}

if (!is_session_valid()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autenticado'], JSON_OUT);
    exit;
}

// Obtener parámetros
$user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : null;
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

// Validar token CSRF
if (!validate_csrf_token($csrf_token)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token CSRF inválido'], JSON_OUT);
    exit;
}

// Regenerar token CSRF
regenerate_csrf_token();

// Validaciones
if (empty($new_password) || empty($confirm_password)) {
    echo json_encode(['success' => false, 'message' => 'Las contraseñas son requeridas'], JSON_OUT);
    exit;
}

if ($new_password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden'], JSON_OUT);
    exit;
}

// Validar longitud mínima (8 caracteres)
if (strlen($new_password) < 8) {
    echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres'], JSON_OUT);
    exit;
}

// Validar complejidad (al menos una mayúscula, un número)
if (!preg_match('/[A-Z]/', $new_password)) {
    echo json_encode(['success' => false, 'message' => 'La contraseña debe contener al menos una letra mayúscula'], JSON_OUT);
    exit;
}

if (!preg_match('/[0-9]/', $new_password)) {
    echo json_encode(['success' => false, 'message' => 'La contraseña debe contener al menos un número'], JSON_OUT);
    exit;
}

// Determinar qué usuario actualizar
if ($user_id === null) {
    // Actualizar contraseña del usuario actual
    $user_id = $_SESSION['id'];
} else {
    // Verificar permisos de administrador para actualizar otros usuarios
    if (!is_admin()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'No tiene permisos para actualizar otros usuarios'], JSON_OUT);
        exit;
    }
}

// Validar que el usuario existe
$stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado'], JSON_OUT);
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// Generar hash bcrypt
$new_hash = hash_password($new_password);

if ($new_hash === false) {
    echo json_encode(['success' => false, 'message' => 'Error al generar hash de contraseña'], JSON_OUT);
    exit;
}

// Actualizar en base de datos
$update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$update_stmt->bind_param("ss", $new_hash, $user_id);

if ($update_stmt->execute()) {
    // Log de seguridad
    security_log(
        'PASSWORD_CHANGE',
        'Contraseña actualizada para usuario: ' . $user['username'],
        $_SESSION['usuario']
    );
    
    echo json_encode([
        'success' => true,
        'message' => 'Contraseña actualizada exitosamente',
        'username' => $user['username']
    ], JSON_OUT);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña'], JSON_OUT);
}

$update_stmt->close();
$conn->close();
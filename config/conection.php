<?php
// Incluir setup.php para inicializar sesión y seguridad
include('setup.php');

// La sesión está iniciada a través de init_security() en setup.php

header('Content-Type: application/json; charset=UTF-8');

// Prevenir acceso directo
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['Tipo' => 'error', 'Title' => 'Error', 'Mensaje' => 'Método no permitido']);
    exit;
}

if (isset($_POST["Accion"]) && $_POST["Accion"] == 'login') {
    login();
}

/**
 * Función de login segura con bcrypt
 * Usa prepared statements para prevenir SQL injection
 * y password_verify para verificar contraseñas
 */
function login()
{
    global $conn;

    // Sanitizar entradas
    $username = isset($_POST["name"]) ? trim($_POST["name"]) : '';
    $password = isset($_POST["pass"]) ? $_POST["pass"] : '';

    // Validación básica
    if (empty($username) || empty($password)) {
        $message = [
            'Tipo' => 'error',
            'Title' => 'Error de validación',
            'Mensaje' => 'Usuario y contraseña son requeridos',
            'nombreusuario' => '',
            'url' => '',
            'privilegeSet' => '',
        ];
        echo json_encode($message, JSON_OUT);
        return;
    }

    // Usar prepared statement para prevenir SQL injection
    // Traer profile_id y el slug del perfil para determinar permisos
    $stmt = $conn->prepare("
        SELECT u.id, u.password, u.first_name, u.last_name, u.profile_id, p.slug
        FROM users u
        LEFT JOIN profiles p ON u.profile_id = p.id
        WHERE u.username = ?
    ");

    if (!$stmt) {
        $message = [
            'Tipo' => 'error',
            'Title' => 'Error del sistema',
            'Mensaje' => 'Error en la consulta de usuario',
            'nombreusuario' => '',
            'url' => '',
            'privilegeSet' => '',
        ];
        echo json_encode($message, JSON_OUT);
        return;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hash = $row['password'];
        $userFullName = $row['first_name'] . ' ' . $row['last_name'];
        $profileSlug = $row['slug'] ?? 'visualizador'; // Valor por defecto
        $privilegeSet = $profileSlug; // Usar el slug del perfil

        // Verificar contraseña con password_verify (soporta MD5 y Bcrypt)
        $password_valid = false;
        
        // Primero intentar con bcrypt (más seguro)
        if (strlen($stored_hash) === 60 && 
            (substr($stored_hash, 0, 4) === '$2y$' || substr($stored_hash, 0, 4) === '$2a$')) {
            $password_valid = password_verify($password, $stored_hash);
        } 
        // Si no, intentar con MD5 (para compatibilidad durante migración)
        else if (strlen($stored_hash) === 32 && ctype_xdigit($stored_hash)) {
            $password_valid = (md5($password) === $stored_hash);
        }

        if ($password_valid) {
            // Regenerar ID de sesión para prevenir session fixation
            session_regenerate_id(true);

            // Determinar archivo de destino según perfil
            $file = ($profileSlug === 'admin' || $profileSlug === 'root')
                ? 'dashboard.php'
                : 'medicines_l.php';

            // Guardar datos en sesión
            $_SESSION['id'] = $row['id'];
            $_SESSION['usuario'] = $userFullName;
            $_SESSION['privilege'] = $privilegeSet;
            $_SESSION['last_activity'] = time();

            // Construir URL
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $base = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/';
            $url = $base . 'pages/' . $file;

            $message = [
                'Tipo' => 'success',
                'Title' => 'Éxito',
                'Mensaje' => 'Conexión Exitosa',
                'nombreusuario' => $userFullName,
                'url' => $url,
                'privilegeSet' => $privilegeSet,
            ];
            echo json_encode($message, JSON_OUT);
            exit;
        } else {
            // Contraseña incorrecta
            $message = [
                'Tipo' => 'error',
                'Title' => 'Error de autenticación',
                'Mensaje' => 'Usuario o contraseña incorrectos',
                'nombreusuario' => '',
                'url' => '',
                'privilegeSet' => '',
            ];
            echo json_encode($message, JSON_OUT);
            exit;
        }
    } else {
        // Usuario no encontrado
        $message = [
            'Tipo' => 'error',
            'Title' => 'Error de autenticación',
            'Mensaje' => 'Usuario no encontrado',
            'nombreusuario' => '',
            'url' => '',
            'privilegeSet' => '',
        ];
        echo json_encode($message, JSON_OUT);
        exit;
    }

    $stmt->close();
}

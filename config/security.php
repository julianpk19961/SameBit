<?php
/**
 * Security.php - Funciones de seguridad centralizadas
 * 
 * Este archivo contiene funciones de seguridad para todo el sistema:
 * - Hash y verificación de contraseñas
 * - Tokens CSRF
 * - Sanitización de datos
 * - Validación de sesiones
 */

/**
 * Generar hash seguro de contraseña usando bcrypt
 * 
 * @param string $password Contraseña en texto plano
 * @return string|false Hash bcrypt o false si falla
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT, [
        'cost' => 12 // Costo de 12 (balance seguridad/performance)
    ]);
}

/**
 * Verificar contraseña contra hash almacenado
 * Soporta tanto bcrypt como MD5 (para migración)
 * 
 * @param string $password Contraseña en texto plano
 * @param string $stored_hash Hash almacenado en base de datos
 * @return bool True si la contraseña es válida
 */
function verify_password($password, $stored_hash) {
    // Si es bcrypt (60 caracteres, empieza con $2y$ o $2a$)
    if (strlen($stored_hash) === 60 && 
        (substr($stored_hash, 0, 4) === '$2y$' || substr($stored_hash, 0, 4) === '$2a$')) {
        return password_verify($password, $stored_hash);
    }
    
    // Si es MD5 (32 caracteres hexadecimales) - solo para migración
    if (strlen($stored_hash) === 32 && ctype_xdigit($stored_hash)) {
        return md5($password) === $stored_hash;
    }
    
    return false;
}

/**
 * Generar token CSRF para formularios
 * 
 * @return string Token CSRF
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validar token CSRF de un formulario
 * 
 * @param string $token Token recibido del formulario
 * @return bool True si el token es válido
 */
function validate_csrf_token($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Regenerar token CSRF (usar después de cada validación)
 */
function regenerate_csrf_token() {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Imprimir campo oculto CSRF para formularios HTML
 * 
 * @return string Campo HTML con token CSRF
 */
function csrf_field() {
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

/**
 * Sanitizar string para prevenir XSS
 * 
 * @param string $data String a sanitizar
 * @return string String sanitizado
 */
function sanitize_string($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Sanitizar entero
 * 
 * @param mixed $data Valor a sanitizar
 * @return int|null Entero sanitizado o null si no es válido
 */
function sanitize_int($data) {
    if (is_numeric($data)) {
        return intval($data);
    }
    return null;
}

/**
 * Validar email
 * 
 * @param string $email Email a validar
 * @return bool True si es válido
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validar que un string no esté vacío y tenga longitud máxima
 * 
 * @param string $data String a validar
 * @param int $min_length Longitud mínima (default: 1)
 * @param int $max_length Longitud máxima (default: 255)
 * @return bool True si es válido
 */
function validate_string_length($data, $min_length = 1, $max_length = 255) {
    $length = strlen($data);
    return $length >= $min_length && $length <= $max_length;
}

/**
 * Verificar si la sesión es válida y no ha expirado
 * 
 * @return bool True si la sesión es válida
 */
function is_session_valid() {
    if (!isset($_SESSION['id']) || !isset($_SESSION['usuario'])) {
        return false;
    }
    
    // Verificar timeout de inactividad (30 minutos)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        // Sesión expirada
        session_destroy();
        return false;
    }
    
    // Actualizar última actividad
    $_SESSION['last_activity'] = time();
    return true;
}

/**
 * Verificar si el usuario tiene privilegios de administrador
 * 
 * @return bool True si es admin o root
 */
function is_admin() {
    return isset($_SESSION['privilege']) && 
           in_array($_SESSION['privilege'], ['admin', 'root']);
}

/**
 * Redirigir si no está autenticado
 */
function require_auth() {
    if (!is_session_valid()) {
        session_destroy();
        header('Location: ' . $GLOBALS['index'] . 'pages/login.php');
        exit;
    }
}

/**
 * Redirigir si no es administrador
 */
function require_admin() {
    require_auth();
    if (!is_admin()) {
        header('Location: ' . $GLOBALS['index'] . 'pages/medicines_l.php');
        exit;
    }
}

/**
 * Prevenir acceso directo a archivos PHP
 * Define una constante que deben incluir los archivos protegidos
 */
define('SECURE_ACCESS', true);

/**
 * Verificar acceso seguro
 */
function check_secure_access() {
    if (!defined('SECURE_ACCESS')) {
        http_response_code(403);
        die('Acceso directo no permitido');
    }
}

/**
 * Log de actividades de seguridad (para auditoría)
 * 
 * @param string $action Acción realizada
 * @param string $details Detalles adicionales
 * @param string $user Usuario (default: usuario actual)
 */
function security_log($action, $details = '', $user = null) {
    if ($user === null && isset($_SESSION['usuario'])) {
        $user = $_SESSION['usuario'];
    } elseif ($user === null) {
        $user = 'anonymous';
    }
    
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'user' => $user,
        'action' => $action,
        'details' => $details,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    // Guardar en archivo de log
    $log_file = __DIR__ . '/../logs/security.log';
    $log_line = json_encode($log_entry) . PHP_EOL;
    file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);
}

/**
 * Headers de seguridad HTTP
 */
function set_security_headers() {
    // Prevenir MIME sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Prevenir clickjacking
    header('X-Frame-Options: DENY');
    
    // Prevenir XSS
    header('X-XSS-Protection: 1; mode=block');
    
    // Política de referente estricta
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Content Security Policy - Permitir CDNs para scripts, estilos y source maps
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' cdn.jsdelivr.net cdnjs.cloudflare.com cdn.datatables.net code.jquery.com; style-src 'self' 'unsafe-inline' cdn.jsdelivr.net cdnjs.cloudflare.com cdn.datatables.net; img-src 'self' data:; font-src 'self' cdn.jsdelivr.net cdnjs.cloudflare.com; connect-src 'self' https:; media-src 'self' data:;");
}

/**
 * Inicializar configuración de seguridad
 * Debe llamarse al inicio de cada script, ANTES de cualquier output
 */
function init_security() {
    // Configurar parámetros de sesión ANTES de iniciar sesión
    if (session_status() === PHP_SESSION_NONE) {
        // Configurar cookies seguras antes de iniciar sesión
        if (PHP_VERSION_ID < 70300) {
            // PHP < 7.3: usar ini_set
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
            ini_set('session.cookie_samesite', 'Strict');
            ini_set('session.use_strict_mode', 1);
            session_start();
        } else {
            // PHP >= 7.3: usar parámetros de session_start
            session_start([
                'cookie_httponly' => 1,
                'cookie_secure' => isset($_SERVER['HTTPS']) ? 1 : 0,
                'cookie_samesite' => 'Strict',
                'use_strict_mode' => 1
            ]);
        }
    }

    // Renovar temporizador de inactividad en cada petición para que el timer
    // corra desde la última carga de página, no solo desde las llamadas AJAX a Commit.php
    if (isset($_SESSION['id'], $_SESSION['usuario'])) {
        $_SESSION['last_activity'] = time();
    }

    // Establecer headers de seguridad (solo si no se han enviado headers)
    if (!headers_sent()) {
        set_security_headers();
    }
}

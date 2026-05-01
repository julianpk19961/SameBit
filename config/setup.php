<?php
/**
 * setup.php - Configuración global del sistema
 * 
 * Este archivo se incluye en todas las páginas para:
 * - Iniciar sesión de forma segura
 * - Configurar rutas y constantes
 * - Incluir funciones de seguridad
 * 
 * IMPORTANTE: Este archivo debe incluirse al inicio de cada script,
 * antes de cualquier output HTML.
 */

// Incluir conexión a la base de datos
require_once __DIR__ . '/config.php';

// Incluir funciones de seguridad
require_once __DIR__ . '/security.php';

// Inicializar seguridad (sesión, cookies, headers) - DEBE IR ANTES DE CUALQUIER OUTPUT
init_security();

// i18n — must come after session is started
require_once __DIR__ . '/i18n.php';
$app_lang    = detect_language();
$translations = load_language($app_lang);

// Profile slug del usuario actual (disponible en todas las páginas)
$profileSlug = $_SESSION['privilege'] ?? null;

// Configuración de la aplicación
$appName = 'bit-medical';

// Rutas de la aplicación
$index = 'http://localhost:8081/';
$url = '/pages/login.php';
$urldashboard = $index . 'pages/dashboard.php';
$title = $appName;

// Zona horaria
date_default_timezone_set('America/Bogota');

// Configuración de errores (producción: 0, desarrollo: E_ALL)
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores en producción
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Límites de subida
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');

// Codificación
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

<?php
/**
 * Example: Usar PermissionManager en un endpoint AJAX
 * 
 * Este archivo muestra cómo integrar validación de permisos en los endpoints existentes
 * Copiar este patrón en pages/ y config/ cuando necesites validar permisos
 */

require_once 'setup.php';
require_once 'PermissionManager.php';

// Verificar que sea AJAX POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

try {
    // Inicializar gestor de permisos
    $pm = new PermissionManager($pdo, $_SESSION['user_id']);

    // EJEMPLO 1: Validar permiso específico antes de procesar
    // Bloquea con 403 si no tiene permiso
    requirePermission('llamadas_samebit', 'create', $pdo, $_SESSION['user_id']);

    // EJEMPLO 2: Verificar manualmente y devolver JSON
    if (!$pm->hasPermission('medicina_samecomed', 'generate_report_x')) {
        http_response_code(403);
        die(json_encode([
            'success' => false,
            'message' => 'No tienes permisos para generar reportes'
        ]));
    }

    // Si llegó aquí, tiene todos los permisos necesarios
    $data = json_decode(file_get_contents('php://input'), true);

    // Procesar lógica del negocio...
    $result = [
        'success' => true,
        'message' => 'Acción completada exitosamente',
        'user_profile' => $pm->getUserProfile(),
        'data' => $data
    ];

    echo json_encode($result);

} catch (Exception $e) {
    error_log("Error en endpoint AJAX: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar la solicitud'
    ]);
}
?>

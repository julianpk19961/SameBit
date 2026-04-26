<?php
/**
 * Script de migración de contraseñas MD5 a Bcrypt
 * 
 * Este script convierte las contraseñas almacenadas en MD5 a Bcrypt
 * para mejorar la seguridad del sistema.
 * 
 * USO: Ejecutar UNA SOLA VEZ en producción
 * URL: http://localhost:8081/database/migrate_passwords.php
 */

// Incluir configuración
require_once '../config/config.php';

// Verificar que solo se pueda ejecutar desde la línea de comandos o con autenticación
if (php_sapi_name() !== 'cli') {
    // Si es web, verificar si es admin (opcional - para producción)
    // session_start();
    // if (!isset($_SESSION['privilege']) || $_SESSION['privilege'] !== 'root') {
    //     die('Acceso denegado. Solo administradores.');
    // }
}

echo "=== Migración de Contraseñas MD5 a Bcrypt ===\n\n";

// Verificar conexión
if (!$conn) {
    die("Error: No se pudo conectar a la base de datos\n");
}

echo "Conexión a base de datos establecida correctamente.\n\n";

// Contadores
$total_users = 0;
$migrated = 0;
$already_migrated = 0;
$errors = 0;

// Obtener todos los usuarios
$sql = "SELECT id, username, password FROM users";
$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error . "\n");
}

$total_users = $result->num_rows;
echo "Total de usuarios encontrados: $total_users\n\n";

// Preparar statement para actualizar
$update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");

if (!$update_stmt) {
    die("Error al preparar statement: " . $conn->error . "\n");
}

// Procesar cada usuario
while ($user = $result->fetch_assoc()) {
    $user_id = $user['id'];
    $username = $user['username'];
    $current_password = $user['password'];
    
    echo "Procesando usuario: $username (ID: $user_id)\n";
    
    // Verificar si la contraseña ya está en formato bcrypt
    // Las contraseñas bcrypt comienzan con $2y$ o $2a$ y tienen 60 caracteres
    if (strlen($current_password) === 60 && 
        (substr($current_password, 0, 4) === '$2y$' || substr($current_password, 0, 4) === '$2a$')) {
        echo "  └─ Estado: Ya está migrada a Bcrypt ✓\n";
        $already_migrated++;
        continue;
    }
    
    // Verificar si es MD5 (32 caracteres hexadecimales)
    if (strlen($current_password) === 32 && ctype_xdigit($current_password)) {
        echo "  └─ Estado: Es MD5, migrando...\n";
        
        // Generar hash bcrypt
        // password_hash genera un hash seguro con bcrypt por defecto
        $bcrypt_hash = password_hash($current_password, PASSWORD_BCRYPT, [
            'cost' => 12 // Costo de 12 (balance entre seguridad y performance)
        ]);
        
        if ($bcrypt_hash === false) {
            echo "  └─ Error: No se pudo generar hash bcrypt\n";
            $errors++;
            continue;
        }
        
        // Actualizar en base de datos
        $update_stmt->bind_param("ss", $bcrypt_hash, $user_id);
        $update_result = $update_stmt->execute();
        
        if ($update_result) {
            echo "  └─ Estado: Migrada exitosamente ✓\n";
            $migrated++;
        } else {
            echo "  └─ Error: No se pudo actualizar en la base de datos\n";
            $errors++;
        }
    } else {
        echo "  └─ Estado: Formato de contraseña desconocido (longitud: " . strlen($current_password) . ")\n";
        $errors++;
    }
    
    // Pequeña pausa para no saturar el servidor
    usleep(10000); // 10ms
}

// Cerrar statement
$update_stmt->close();

// Mostrar resumen
echo "\n=== RESUMEN DE MIGRACIÓN ===\n";
echo "Total de usuarios: $total_users\n";
echo "Contraseñas migradas: $migrated\n";
echo "Contraseñas ya migradas: $already_migrated\n";
echo "Errores: $errors\n";

if ($errors > 0) {
    echo "\n⚠️  Se presentaron $errors errores durante la migración.\n";
    echo "Revise los usuarios con errores antes de continuar.\n";
} else if ($migrated > 0 || $already_migrated > 0) {
    echo "\n✅ ¡Migración completada exitosamente!\n";
    echo "Todas las contraseñas ahora están protegidas con Bcrypt.\n";
} else {
    echo "\nℹ️  No se encontraron contraseñas para migrar.\n";
}

echo "\n=== PRÓXIMOS PASOS ===\n";
echo "1. Verificar que el login funcione correctamente\n";
echo "2. Eliminar o deshabilitar este script de migración\n";
echo "3. Actualizar config/conection.php para usar password_verify()\n";
echo "\n";

// Liberar resultado
$result->free();

// Cerrar conexión
$conn->close();

echo "Proceso finalizado.\n";
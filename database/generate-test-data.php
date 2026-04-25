#!/usr/bin/env php
<?php
/**
 * SameBit - Generador de Datos de Prueba
 * 
 * Genera 5,000 medicamentos y 10,000 llamadas (registros de prioridades)
 * 
 * Uso: php database/generate-test-data.php
 */

// Incluir configuración
require_once __DIR__ . '/../config/config.php';

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║         SameBit - Generador de Datos de Prueba                 ║\n";
echo "║  Genera 5,000 medicamentos + 10,000 llamadas (llamadas)        ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Verificar conexión
if (!$conn) {
    die("❌ Error: No se pudo conectar a la base de datos\n");
}

echo "✅ Conectado a base de datos: bit_medical\n\n";

// Leer el archivo SQL
$sqlFile = __DIR__ . '/seed-test.sql';
if (!file_exists($sqlFile)) {
    die("❌ Error: No se encontró el archivo $sqlFile\n");
}

$sql = file_get_contents($sqlFile);

// Dividir en queries separadas
$queries = array_filter(array_map('trim', explode(';', $sql)));

$successCount = 0;
$errorCount = 0;
$startTime = microtime(true);

echo "Ejecutando " . count($queries) . " operaciones SQL...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

foreach ($queries as $index => $query) {
    if (empty($query)) continue;
    
    $queryPreview = substr($query, 0, 60);
    
    if ($conn->multi_query($query)) {
        // Consumir todos los resultados
        while ($conn->more_results()) {
            $conn->next_result();
            if ($result = $conn->store_result()) {
                $result->free();
            }
        }
        $successCount++;
        echo "✅ Query " . ($index + 1) . ": " . $queryPreview . "...\n";
    } else {
        $errorCount++;
        echo "❌ Query " . ($index + 1) . ": Error - " . $conn->error . "\n";
    }
}

$endTime = microtime(true);
$executionTime = round($endTime - $startTime, 2);

echo "\n" . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n📊 RESUMEN DE DATOS INSERADOS:\n\n";

// Obtener estadísticas
$stats = [
    'Total Medicamentos' => 'SELECT COUNT(*) as count FROM medicines',
    'Total Llamadas' => 'SELECT COUNT(*) as count FROM priorities',
    'Total Pacientes' => 'SELECT COUNT(*) as count FROM patients',
    'Total Usuarios' => 'SELECT COUNT(*) as count FROM users',
    'Total Entidades (EPS/IPS)' => 'SELECT COUNT(*) as count FROM entities',
    'Total Diagnósticos' => 'SELECT COUNT(*) as count FROM diagnoses',
    'Medicamentos Activos' => 'SELECT COUNT(*) as count FROM medicines WHERE active = 1',
    'Llamadas Aprobadas' => 'SELECT COUNT(*) as count FROM priorities WHERE approved = 1',
];

foreach ($stats as $label => $query) {
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        printf("  • %-30s : %,d\n", $label, $row['count']);
        $result->free();
    }
}

echo "\n📈 Operaciones:\n";
printf("  • Queries exitosas: %d\n", $successCount);
printf("  • Errores: %d\n", $errorCount);
printf("  • Tiempo total: %.2f segundos\n", $executionTime);

echo "\n" . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if ($errorCount === 0) {
    echo "\n✨ ¡Éxito! Los datos de prueba han sido generados correctamente.\n";
    echo "   Puedes acceder a la aplicación en: http://localhost:8081\n";
    echo "   Usuario: admin / Contraseña: admin\n\n";
} else {
    echo "\n⚠️  Se encontraron algunos errores durante la ejecución.\n";
    echo "   Por favor, revisa los errores arriba.\n\n";
}

$conn->close();
?>

<?php
/**
 * Endpoint: Listar pacientes para DataTable
 * POST /config/list_patients.php
 * Response: { data: [...] }
 */

require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['data' => [], 'error' => 'Método no permitido']);
    exit;
}

try {
    require_auth();

    // Detect whether the extended columns have been migrated yet
    $chkActive = $conn->query("SHOW COLUMNS FROM patients LIKE 'active'");
    $hasActive = ($chkActive && $chkActive->num_rows > 0);
    $activeExpr = $hasActive ? 'COALESCE(p.active, 1)' : '1';

    $sql = "
        SELECT
            p.id,
            p.document_type,
            p.document_number,
            p.first_name,
            p.last_name,
            $activeExpr       AS active,
            COALESCE(e.name, '') AS eps_name
        FROM patients p
        LEFT JOIN entities e ON e.id = p.eps_id
        ORDER BY p.last_name, p.first_name
    ";

    $result = $conn->query($sql);
    if (!$result) {
        throw new Exception($conn->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id'              => $row['id'],
            'document_type'   => $row['document_type'],
            'document_number' => $row['document_number'],
            'first_name'      => $row['first_name'],
            'last_name'       => $row['last_name'],
            'active'          => $row['active'],
            'eps_name'        => $row['eps_name'] ?: '—',
        ];
    }

    echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Return 200 + empty data so DataTable renders without a JS error popup;
    // the _error key lets you inspect the real cause in the browser Network tab.
    error_log('list_patients.php: ' . $e->getMessage());
    echo json_encode(['data' => [], '_error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

<?php
require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([], JSON_OUT);
    exit;
}

$q = isset($_POST['q']) ? trim($_POST['q']) : '';

if (strlen($q) < 2) {
    echo json_encode([], JSON_OUT);
    exit;
}

$stmt = $conn->prepare("
    SELECT
        id              AS UUID,
        first_name      AS NOMBRE,
        last_name       AS APELLIDO,
        CONCAT(first_name, ' ', last_name) AS PACIENTE,
        document_number AS DOC_NUMBER,
        document_type   AS DOC_TYPE,
        eps_id          AS EPS,
        ips_id          AS IPS,
        range_level     AS RANGO
    FROM patients
    WHERE document_number LIKE ? OR CONCAT(first_name, ' ', last_name) LIKE ?
    ORDER BY document_number
    LIMIT 10
");

if (!$stmt) {
    echo json_encode([], JSON_OUT);
    exit;
}

$like = '%' . $q . '%';
$stmt->bind_param('ss', $like, $like);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'UUID'       => $row['UUID'],
        'PACIENTE'   => $row['PACIENTE'],
        'NOMBRE'     => $row['NOMBRE'],
        'APELLIDO'   => $row['APELLIDO'],
        'DOC_NUMBER' => $row['DOC_NUMBER'],
        'DOC_TYPE'   => $row['DOC_TYPE'],
        'EPS'        => $row['EPS'],
        'IPS'        => $row['IPS'],
        'RANGO'      => $row['RANGO'],
    ];
}

$stmt->close();
echo json_encode($data, JSON_OUT);

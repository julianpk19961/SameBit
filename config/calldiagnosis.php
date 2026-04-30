<?php
require_once 'setup.php';

$sql    = "SELECT id, code, description FROM diagnoses ORDER BY code";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([], JSON_OUT);
    exit;
}

$json = [];
while ($row = mysqli_fetch_assoc($result)) {
    $json[] = [
        'KP_UUID'     => $row['id'],
        'Codigo'      => $row['code'],
        'Observation' => $row['description']
    ];
}
header('Content-Type: application/json; charset=UTF-8');
echo json_encode($json, JSON_OUT);

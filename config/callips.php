<?php
include 'config.php';

$sql    = "SELECT id, name, nit FROM entities WHERE entity_type_id = (SELECT id FROM entity_types WHERE name = 'IPS') ORDER BY name";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([], JSON_OUT);
    exit;
}

$json = [];
while ($row = mysqli_fetch_assoc($result)) {
    $json[] = [
        'pk_uuid' => $row['id'],
        'name'    => mb_strtoupper($row['name'], 'UTF-8'),
        'nit'     => $row['nit']
    ];
}
header('Content-Type: application/json; charset=UTF-8');
echo json_encode($json, JSON_OUT);

<?php
include 'config.php';
date_default_timezone_set('America/Bogota');

$today = date('Y-m-d');

$sql = "SELECT
    p.id                AS UUID,
    CONCAT(TIME_FORMAT(pr.checkin_time, '%H:%i'), ' - ', pr.first_name, ' ', pr.last_name) AS PACIENTE,
    pr.document_number  AS DOC_NUMBER
FROM priorities pr
INNER JOIN patients p ON pr.patient_id = p.id
WHERE pr.checkin_date = '$today'
ORDER BY pr.checkin_time ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['data' => []]);
    mysqli_close($conn);
    exit;
}

$array = ['data' => []];
while ($row = mysqli_fetch_assoc($result)) {
    $array['data'][] = $row;
}

echo json_encode($array);
mysqli_close($conn);

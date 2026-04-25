<?php
include 'config.php';

$Dni = $_POST['dni'];
$Dni = mysqli_real_escape_string($conn, $Dni);

$sql = "SELECT
    id               AS UUID,
    CONCAT(first_name, ' ', last_name) AS PACIENTE,
    first_name       AS NOMBRE,
    last_name        AS APELLIDO,
    document_number  AS DOC_NUMBER,
    document_type    AS DOC_TYPE,
    eps_id           AS EPS,
    range_level      AS CLASIFICACION
FROM patients
WHERE document_number LIKE '%$Dni%'
ORDER BY document_number DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

$resultCount = mysqli_num_rows($result);
if ($resultCount > 0) {
    while ($data = mysqli_fetch_assoc($result)) {
        $array['data'][] = $data;
    }
} else {
    $array['data'] = ['error' => 'no se encontraron registros'];
}

echo json_encode($array);
mysqli_close($conn);

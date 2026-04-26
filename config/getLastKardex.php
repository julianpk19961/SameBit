<?php
include 'config.php';
$pk_uuid = $_POST['pk_uuid'];

$sql    = "SELECT k.movement_date, mc.name AS category, k.patient_id AS patient, k.type, k.quantity, k.final_quantity, k.bill, k.notes AS comment
           FROM kardex AS k
           INNER JOIN movement_categories AS mc ON mc.id = k.category_id
           WHERE k.medicine_id = '$pk_uuid'
           ORDER BY k.movement_date DESC
           LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo 'Query Error' . mysqli_error($conn);
    return;
}

$resultCount = mysqli_num_rows($result);
if ($resultCount > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'finalQuantity' => $row['final_quantity']
        );
    }
    $jsonkardex = $json;
} else {
    $jsonkardex = 'error';
}

echo json_encode($jsonkardex, JSON_OUT);

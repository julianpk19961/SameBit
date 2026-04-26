<?php
include 'config.php';
$pk_uuid       = $_POST['pk_uuid'];
$responseArray = array();

$sql    = "SELECT id, abbreviation, name FROM movement_categories";
$result = mysqli_query($conn, $sql);
if (!$result) {
    $jsoncategories = 'Query Error' . mysqli_error($conn);
}

$resultCount = mysqli_num_rows($result);
if ($resultCount > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'KP_UUID' => $row['id'],
            'abbr'    => $row['abbreviation'],
            'name'    => $row['name']
        );
    }
    $jsoncategories = $json;
} else {
    $jsoncategories = 'error';
}
array_push($responseArray, $jsoncategories);

$resultCount = '';

$sql    = "SELECT k.movement_date, mc.name AS category, k.patient_id AS patient, k.type, k.quantity, k.final_quantity, k.bill, k.notes AS comment
           FROM kardex AS k
           INNER JOIN movement_categories AS mc ON mc.id = k.category_id
           WHERE k.medicine_id = '$pk_uuid'
           ORDER BY k.movement_date DESC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    $jsonstring = 'Query Error' . mysqli_error($conn);
}

$resultCount = mysqli_num_rows($result);
if ($resultCount > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'zCrea'         => $row['movement_date'],
            'category'      => $row['category'],
            'patient'       => $row['patient'],
            'bill'          => $row['bill'],
            'type'          => $row['type'],
            'quantity'      => $row['quantity'],
            'finalQuantity' => $row['final_quantity'],
            'comment'       => $row['comment'],
        );
    }
    $jsonkardex = $json;
} else {
    $jsonkardex = 'error';
}
array_push($responseArray, $jsonkardex);

echo json_encode($responseArray, JSON_OUT);

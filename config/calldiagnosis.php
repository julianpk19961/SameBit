<?php
include 'config.php';

$sql    = "SELECT id, code, description FROM diagnoses ORDER BY code";
$result = mysqli_query($conn, $sql);
$count  = mysqli_num_rows($result);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

if ($count > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'KP_UUID'     => $row['id'],
            'Codigo'      => $row['code'],
            'Observation' => utf8_encode($row['description'])
        );
    }
    echo json_encode($json);
} else {
    echo '[]';
}

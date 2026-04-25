<?php
include 'config.php';

$sql = "SELECT
    m.*,
    (SELECT COUNT(*) FROM kardex k WHERE k.medicine_id = m.id) AS nrows
FROM medicines m
ORDER BY m.name ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

$resultCount = mysqli_num_rows($result);

if ($resultCount > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'KP_UUID'    => $row['id'],
            'nombre'     => $row['name'],
            'referencia' => $row['reference'],
            'observacion'=> $row['notes'],
            'z_xOne'     => $row['active'],
            'nrows'      => $row['nrows']
        );
    }
    $jsonstring = json_encode($json);
} else {
    $jsonstring = 'error';
}

echo $jsonstring;

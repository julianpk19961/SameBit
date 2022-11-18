<?php

include 'config.php';

$sql = "SELECT
*,
(SELECT COUNT(*) FROM kardex k WHERE k.FK_Medicine = m.KP_UUID ) AS nrows
FROM
medicines m
ORDER BY
m.nombre ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

$resultCount = mysqli_num_rows($result);

if ($resultCount > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'KP_UUID' => $row['KP_UUID'],
            'nombre' => $row['nombre'],
            'referencia' => $row['referencia'],
            'observacion' => $row['observacion'],
            'z_xOne' => $row['z_xOne'],
            'nrows' => $row['nrows']
        );
    }
    $jsonstring = json_encode($json);
} else {
    $jsonstring = 'error';
}

echo $jsonstring;

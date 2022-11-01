<?php

include 'config.php';
$searchbox = $_POST['searchbox'];

$sql = "SELECT  id,KP_UUID,nombre,referencia,observacion FROM medicines WHERE nombre LIKE '%$searchbox%' OR referencia LIKE '%$searchbox%' ORDER BY id ASC";

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
            'observacion' => $row['observacion']
        );
    }
    $jsonstring = json_encode($json);
} else {
    $jsonstring = 'error';
}

echo $jsonstring;

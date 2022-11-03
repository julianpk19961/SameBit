<?php

include 'config.php';

$PK_UUID = isset($_POST["PK_UUID"]) ? $_POST["PK_UUID"] : '';

$sql = "DELETE FROM medicines WHERE PK_UUID =  ";


$result = mysqli_query($conn, $sql);
if (!$result) {
    //     error
    $result = 'Query Error' . mysqli_error($conn);
} else {

    $id = mysqli_insert_id($conn);

    $sql = "SELECT * FROM medicines WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $json = array();
        $json = mysqli_fetch_array($result);
        $result = json_encode($json);
    }

    if (!$result) {
        $result = 'Error al consultar registro creado id: ' . $id . '-' . mysqli_error($conn);
    }
}

echo $result;

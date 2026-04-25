<?php
include 'config.php';

$name      = isset($_POST["name"])      ? $_POST["name"]      : '';
$reference = isset($_POST["reference"]) ? $_POST["reference"] : '';
$notes     = isset($_POST["observation"]) ? $_POST["observation"] : '';

mysqli_query($conn, "SET @new_uuid = UUID()");
$sql    = "INSERT INTO medicines (id, name, reference, notes) VALUES (@new_uuid, '$name', '$reference', '$notes')";
$result = mysqli_query($conn, $sql);

if (!$result) {
    $result = 'Query Error' . mysqli_error($conn);
} else {
    $uuid_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT @new_uuid AS uuid"));
    $new_uuid = $uuid_row['uuid'];

    $sql    = "SELECT * FROM medicines WHERE id = '$new_uuid'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $json   = mysqli_fetch_array($result);
        $result = json_encode($json);
    }

    if (!$result) {
        $result = 'Error al consultar registro creado: ' . mysqli_error($conn);
    }
}

echo $result;

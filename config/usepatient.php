<?php

include 'config.php';

if (isset($_POST['pk_uuid'])) {
    $pk_uuid = $_POST['pk_uuid'];

    $sql = "SELECT KP_UUID,Name0,LastName0,Dni,documentType,FK_Eps,FK_Range,FK_Ips FROM patients WHERE KP_UUID = '$pk_uuid' ";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die('Query Error' . mysqli_error($conn));
    }

    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'pk_uuid' => $row['KP_UUID'],
            'name' => $row['Name0'],
            'lastname' => $row['LastName0'],
            'dni' => $row['Dni'],
            'documentType' => $row['documentType'],
            'eps' => $row['FK_Eps'],
            'range' => $row['FK_Range'],
            'ips' => $row['FK_Ips'],
        );
    }
    $jsonString = json_encode($json[0]);
    echo $jsonString;
}

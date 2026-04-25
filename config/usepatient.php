<?php
include 'config.php';

if (isset($_POST['pk_uuid'])) {
    $pk_uuid = $_POST['pk_uuid'];

    $sql    = "SELECT id, first_name, last_name, document_number, document_type, eps_id, range_level, ips_id
               FROM patients
               WHERE id = '$pk_uuid'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die('Query Error' . mysqli_error($conn));
    }

    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'pk_uuid'      => $row['id'],
            'name'         => $row['first_name'],
            'lastname'     => $row['last_name'],
            'dni'          => $row['document_number'],
            'documentType' => $row['document_type'],
            'eps'          => $row['eps_id'],
            'range'        => $row['range_level'],
            'ips'          => $row['ips_id'],
        );
    }
    echo json_encode($json[0]);
}

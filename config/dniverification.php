<?php
include 'config.php';
$Dni = $_POST['dni'];

$sql    = "SELECT id, first_name, last_name, document_number, document_type, eps_id, range_level
           FROM patients
           WHERE document_number LIKE '$Dni%'
           ORDER BY document_number DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

$resultCount = mysqli_num_rows($result);
if ($resultCount > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'PK_UUID'      => $row['id'],
            'Name'         => $row['first_name'],
            'LastName'     => $row['last_name'],
            'dni'          => $row['document_number'],
            'documentType' => $row['document_type'],
            'eps'          => $row['eps_id'],
            'range'        => $row['range_level']
        );
    }
    echo json_encode($json);
} else {
    echo 'error';
}

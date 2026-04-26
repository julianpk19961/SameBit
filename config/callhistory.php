<?php
include 'config.php';
$Dni = $_POST['dni'];

$sql = "SELECT b.response_date, b.response_time, b.created_by, b.reception_notes
        FROM priorities b
        INNER JOIN patients p ON p.id = b.patient_id
        WHERE p.document_number = '$Dni'
        ORDER BY b.response_date DESC, b.response_time DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

$resultCount = mysqli_num_rows($result);
if ($resultCount > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'commentdate' => $row['response_date'],
            'commenttime' => $row['response_time'],
            'createdUser' => $row['created_by'],
            'comment0'    => $row['reception_notes']
        );
    }
    echo json_encode($json, JSON_OUT);
} else {
    echo 'error';
}

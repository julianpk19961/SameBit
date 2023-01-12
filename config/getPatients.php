<?php

include 'config.php';
$Dni = $_POST['dni'];

$sql = "SELECT  KP_UUID,Name0,LastName0,Dni,documentType,FK_Eps,FK_Range FROM patients WHERE Dni LIKE '$Dni%' ORDER BY Dni DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

$resultCount = mysqli_num_rows($result);

if ($resultCount > 0) {

    while ($data = $result->fetch_object()) {
        $data_array[] = array(
            $data->KP_UUID,
            $data->Dni,
            $data->Name0 . ' ' . $data->LastName0,
            '',
        );
    }

    $new_array = array('data' => $data_array);
    $jsonstring = json_encode($new_array);
} else {
    $jsonstring = 'error';
}

echo $jsonstring;
// ,
//             $data->LastName0,
//             $data->documentType,
//             $data->FK_Eps,
//             $data->FK_Range
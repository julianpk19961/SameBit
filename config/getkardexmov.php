<?php

include 'config.php';
$pk_uuid = $_POST['pk_uuid'];
$responseArray = array();


$sql = "SELECT KP_UUID,abbr,`name` FROM  movcategories";
$result = mysqli_query($conn, $sql);
if (!$result) {
    $jsoncategories = ('Query Error' . mysqli_error($conn));
}
$resultCount = mysqli_num_rows($result);

if ($resultCount > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'KP_UUID' => $row['KP_UUID'],
            'abbr' => $row['abbr'],
            'name' => $row['name']
        );
    }
    $jsoncategories = $json;
    // $jsoncategories = json_encode($json);
} else {
    $jsoncategories = 'error';
}
array_push($responseArray, $jsoncategories);

$resultCount = '';

$sql = "SELECT k.zCrea,mc.`name` AS category,k.FK_Patient AS patient,k.`type`,k.quantity,k.finalQuantity 
FROM kardex AS k 
INNER JOIN movcategories AS mc ON mc.KP_UUID = k.FK_Category
WHERE k.FK_Medicine = '$pk_uuid'
ORDER BY k.zCrea DESC";

$result = mysqli_query($conn, $sql);
if (!$result) {
    $jsonstring = ('Query Error' . mysqli_error($conn));
}

$resultCount = mysqli_num_rows($result);

if ($resultCount > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'zCrea' => $row['zCrea'],
            'category' => $row['category'],
            'patient' => $row['patient'],
            'type' => $row['type'],
            'quantity' => $row['quantity'],
            'finalQuantity' => $row['finalQuantity']
        );
    }
    // $jsonstring = json_encode($json);
    $jsonkardex = $json;
} else {
    $jsonkardex = 'error';
}

array_push($responseArray, $jsonkardex);


// // $resultCount = '';
// $sql = "SELECT MAX(finalQuantity) AS SALDO FROM kardex WHERE FK_Medicine = '$pk_uuid' AND zCrea IN (SELECT MAX(zCrea) FROM kardex WHERE FK_Medicine = '$pk_uuid');";

// $result = mysqli_query($conn, $sql);
// $jsonFinalQuantity = mysqli_fetch_array($result);

// array_push($responseArray, $jsonFinalQuantity);

echo json_encode($responseArray);


// echo $jsonstring;

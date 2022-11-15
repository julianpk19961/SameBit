<?php

use LDAP\Result;

include 'config.php';
$medicine = isset($_POST["pk_uuid"]) ? $_POST["pk_uuid"] : '';
$patient = isset($_POST["patient"]) ? $_POST["patient"] : '';
$bill = isset($_POST["bill"]) ? $_POST["bill"] : '';
$category = isset($_POST["category"]) ? $_POST["category"] : '';
$quantity = isset($_POST["quantity"]) ? $_POST["quantity"] : '';




$sql = "SELECT MAX(finalQuantity) AS SALDO FROM kardex WHERE FK_Medicine = '$medicine' AND zCrea IN (SELECT MAX(zCrea) FROM kardex WHERE FK_Medicine = '$medicine');";
$result = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($result);
$saldoinicial = $result['SALDO'] == '' ? 0 : $result['SALDO'];


$sql = "SELECT `type` FROM  movcategories WHERE KP_UUID='$category'";
$result = mysqli_query($conn, $sql);
if (!$result) {
    $jsoncategories = ('Query Error' . mysqli_error($conn));
}
$resultCount = mysqli_num_rows($result);
$result = mysqli_fetch_assoc($result);
$type = $result['type'];
$operator = ($type == 1 ? '+' : '-');
$total = eval('return ' . $saldoinicial . $operator . $quantity . ';');

echo $total;

if ($total < 0) {
    $result = 'error-la cantidad supera la existencia en el kardex';
}

if ($total >= 0) {
    $sql = "INSERT INTO kardex (FK_Medicine,FK_Patient,FK_Category,`Type`,inicialQuantity,quantity,finalQuantity,bill) VALUES ( '$medicine','$patient','$category','$type',$saldoinicial,$quantity,$total,$bill) ";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $result = ('Query error' . mysqli_error($conn));
    } else {
        $result = 'success';
    }
}


echo $result;

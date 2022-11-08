<?php

include 'config.php';
$medicine = isset($_POST["pk_uuid"])?$_POST["pk_uuid"]:'';
$patient = isset($_POST["patient"])?$_POST["patient"]:'';
$bill = isset($_POST["bill"])?$_POST["bill"]:'';
$category = isset($_POST["category"])?$_POST["category"]:'';
$quantity = isset($_POST["quantity"])?$_POST["quantity"]:'';
$finalquantity = isset($_POST["finalquantity"])?$_POST["finalquantity"]:'';


$sql = "SELECT `type` FROM  movcategories WHERE KP_UUID='$category'";
$result = mysqli_query($conn, $sql);
if (!$result) {
    $jsoncategories = ('Query Error' . mysqli_error($conn));
}

$resultCount = mysqli_num_rows($result);
$result = mysqli_fetch_array($result);


echo json_encode($result);


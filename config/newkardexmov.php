<?php

include 'config.php';
date_default_timezone_set('America/Bogota');

$medicine = isset($_POST["pk_uuid"]) ? $_POST["pk_uuid"] : '';
$date = isset($_POST["date"]) ? $_POST["date"] : '';
$patient = isset($_POST["patient"]) ? $_POST["patient"] : '';
$bill = isset($_POST["bill"]) ? $_POST["bill"] : '';
$category = isset($_POST["category"]) ? $_POST["category"] : '';
$quantity = isset($_POST["quantity"]) ? $_POST["quantity"] : '';
$comment = isset($_POST["comment"]) ? $_POST["comment"] : '';


$sql = "SELECT MAX(dateUserCrea) AS LASTDATE FROM kardex WHERE FK_Medicine = '$medicine'";
$result = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($result);
$lastTimeStamp = $result['LASTDATE'] == '' ? 0 : $result['LASTDATE'];
$lastDate_date = explode(' ', $lastTimeStamp)[0];

if ($lastDate_date > $date) {
    echo 'error-No puede ingresar fechas inferiores a la última registrada';
    return false;
}

function verifyTime($lastTimeStamp, $date)
{
    $lastDate_time = strtotime(explode(' ', $lastTimeStamp)[1]);
    $hour = date('H', $lastDate_time);
    $LastDay = date('d', strtotime($lastTimeStamp));
    $currentDay = date('d', strtotime($date));
    echo $LastDay . '  ' . $currentDay;

    if ($LastDay < $currentDay) {
        $new_time = strtotime('23:00:00');
    } else {
        $new_time = strtotime($hour < 23 ? '23:00:00' : '+ 1 seconds', $lastDate_time);
    }

    $new_time = date('H:i:s', $new_time);
    $new_date = $date . ' ' . $new_time;

    return $new_date;
}

if ($lastDate_date <= $date and $date < date('Y-m-d')) {
    $date = verifyTime($lastTimeStamp, $date);
} else {
    $date = date('Y-m-d H:i:s');
}

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

if ($total < 0) {
    $result = 'error-la cantidad supera la existencia en el kardex';
}


if ($total >= 0) {
    $sql = "INSERT INTO kardex (FK_Medicine,FK_Patient,FK_Category,`Type`,inicialQuantity,quantity,finalQuantity,bill,dateUserCrea,comment) VALUES ( '$medicine','$patient','$category','$type',$saldoinicial,$quantity,$total,'$bill','$date','$comment') ";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $result = ('Query error' . mysqli_error($conn));
    } else {
        $result = $medicine;
    }
}


return $result;

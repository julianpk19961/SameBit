<?php
include 'config.php';
date_default_timezone_set('America/Bogota');

function sanitizeInput($conn, $input)
{
    return mysqli_real_escape_string($conn, $input);
}

$medicine = isset($_POST["pk_uuid"])  ? sanitizeInput($conn, $_POST["pk_uuid"])  : '';
$date     = isset($_POST["date"])     ? sanitizeInput($conn, $_POST["date"])     : '';
$patient  = isset($_POST["patient"])  ? sanitizeInput($conn, $_POST["patient"])  : '';
$bill     = isset($_POST["bill"])     ? sanitizeInput($conn, $_POST["bill"])     : '';
$category = isset($_POST["category"]) ? sanitizeInput($conn, $_POST["category"]) : '';
$quantity = isset($_POST["quantity"]) ? sanitizeInput($conn, $_POST["quantity"]) : '';
$comment  = isset($_POST["comment"])  ? sanitizeInput($conn, $_POST["comment"])  : '';

$sql    = "SELECT MAX(movement_date) AS last_date FROM kardex WHERE medicine_id = '$medicine'";
$result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
$lastTimeStamp = $result['last_date'] == '' ? 0 : $result['last_date'];
$lastDate_date = explode(' ', $lastTimeStamp)[0];

if ($lastDate_date > $date) {
    echo 'error-No puede ingresar fechas inferiores a la última registrada';
    return false;
}

function verifyTime($lastTimeStamp, $date)
{
    $lastDate_time = strtotime(explode(' ', $lastTimeStamp)[1]);
    $hour          = date('H', $lastDate_time);
    $LastDay       = date('d', strtotime($lastTimeStamp));
    $currentDay    = date('d', strtotime($date));
    echo $LastDay . '  ' . $currentDay;

    if ($LastDay < $currentDay) {
        $new_time = strtotime('23:00:00');
    } else {
        $new_time = strtotime($hour < 23 ? '23:00:00' : '+ 1 seconds', $lastDate_time);
    }

    return $date . ' ' . date('H:i:s', $new_time);
}

if ($lastDate_date <= $date && $date < date('Y-m-d')) {
    $date = verifyTime($lastTimeStamp, $date);
} else {
    $date = date('Y-m-d H:i:s');
}

$sql    = "SELECT MAX(final_quantity) AS SALDO FROM kardex WHERE medicine_id = '$medicine' AND created_at IN (SELECT MAX(created_at) FROM kardex WHERE medicine_id = '$medicine')";
$result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
$saldoinicial = $result['SALDO'] == '' ? 0 : $result['SALDO'];

$sql    = "SELECT type FROM movement_categories WHERE id = '$category'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo 'Query Error' . mysqli_error($conn);
    return false;
}

$result = mysqli_fetch_assoc($result);
$type   = $result['type'];

if ($type != 2) {
    $operator = ($type == 1 ? '+' : '-');
    $total    = eval('return ' . $saldoinicial . $operator . $quantity . ';');

    if ($total < 0) {
        $result = 'error-la cantidad supera la existencia en el kardex';
    }
} else {
    $total   = $quantity;
    $patient = (isset($appName) ? $appName : 'bit-medical') . ' - VISITA';
}

if ($total >= 0) {
    $sql    = "INSERT INTO kardex (medicine_id, patient_id, category_id, type, initial_quantity, quantity, final_quantity, bill, movement_date, notes)
               VALUES ('$medicine', '$patient', '$category', '$type', $saldoinicial, $quantity, $total, '$bill', '$date', '$comment')";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $result = 'Query error' . mysqli_error($conn);
    } else {
        $result = $medicine;
    }
}

return $result;

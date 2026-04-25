<?php
include('config.php');
session_start();

header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('America/Bogota');

$PK_UUID       = isset($_POST["pk_uuid"])          ? $_POST["pk_uuid"]          : '';
$FK_EPS        = isset($_POST["Eps"])               ? $_POST["Eps"]               : '';
$FK_Ips        = isset($_POST["ips"])               ? $_POST["ips"]               : '';
$FK_Range      = isset($_POST["EpsClassification"]) ? $_POST["EpsClassification"] : '';
$FK_Diagnosis  = isset($_POST["diagnosis"])         ? $_POST["diagnosis"]         : '';
$dni           = isset($_POST["dni"])               ? $_POST["dni"]               : '';
$documenttype  = isset($_POST["documenttype"])      ? $_POST["documenttype"]      : '';
$name          = isset($_POST["name"])              ? $_POST["name"]              : '';
$lastname      = isset($_POST["lastname"])          ? $_POST["lastname"]          : '';
$contactype    = isset($_POST["contacttype"])       ? $_POST["contacttype"]       : '';
$approved      = isset($_POST["approved"])          ? $_POST["approved"]          : '';
$comment       = isset($_POST["ObservationIn"])     ? $_POST["ObservationIn"]     : '';
$sentby        = isset($_POST["SentBy"])            ? $_POST["SentBy"]            : '';
$statuseps     = isset($_POST["EpsStatus"])         ? $_POST["EpsStatus"]         : '';
$callsnumber   = isset($_POST["CallNumber"])        ? $_POST["CallNumber"]        : '';
$exhibitNine   = isset($_POST["exhibitNine"])       ? $_POST["exhibitNine"]       : 0;
$exhibitTen    = isset($_POST["exhibitTen"])        ? $_POST["exhibitTen"]        : 0;
$sendTo        = isset($_POST["sendTo"])            ? $_POST["sendTo"]            : '';
$commentOut    = isset($_POST["ObservationOut"])    ? $_POST["ObservationOut"]    : '';

$checkInDateTime  = isset($_POST["checkInDate"])  ? new DateTime($_POST["checkInDate"])  : '';
$checkOutDateTime = isset($_POST["commentDate"])  ? new DateTime($_POST["commentDate"])  : '';
$attetionDateTime = isset($_POST["AtentionDate"]) ? new DateTime($_POST["AtentionDate"]) : '';

$checkInDate  = $checkInDateTime->format('Y-m-d');
$checkInTime  = $checkInDateTime->format('H:i:s');
$commentDate  = $checkOutDateTime->format('Y-m-d');
$commentTime  = $checkOutDateTime->format('H:i:s');
$appointmentdate = $attetionDateTime->format('Y-m-d');
$appointmenttime = $attetionDateTime->format('H:i:s');

$comunicationDiff = $checkOutDateTime->diff($checkInDateTime);
$attetionDiff     = $attetionDateTime->diff($checkInDateTime);

$responseDayDiff  = $comunicationDiff->format('%a');
$responseTimeDiff = $comunicationDiff->format('%h:%i');
$attetionDayDiff  = $attetionDiff->format('%a');
$attetionTimeDiff = $attetionDiff->format('%h:%i');

$username = $_SESSION['usuario'];

if (empty($PK_UUID)) {
    $sql = "INSERT INTO patients (id, document_number, document_type, first_name, last_name, eps_id, range_level, ips_id, created_by, updated_by)
            VALUES (UUID(), '$dni', '$documenttype', '$name', '$lastname', '$FK_EPS', '$FK_Range', '$FK_Ips', '$username', '$username')";
} else {
    $sql = "UPDATE patients SET
                document_number = '$dni',
                document_type   = '$documenttype',
                first_name      = '$name',
                last_name       = '$lastname',
                eps_id          = '$FK_EPS',
                ips_id          = '$FK_Ips',
                range_level     = '$FK_Range',
                updated_by      = '$username'
            WHERE document_number = '$dni'";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

$sql    = "SELECT id FROM patients WHERE document_number = '$dni'";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die('Query Error' . mysqli_error($conn));
}
while ($row = mysqli_fetch_array($result)) {
    $PK_UUID = $row['id'];
}

$cols = "id, patient_id, eps_id, ips_id, range_level, diagnosis_id, document_number, first_name,
    last_name, contact_type, approved, sent_by, eps_status, calls_count, reception_notes, created_by,
    updated_by, annex_nine, annex_ten, sent_to, outgoing_notes, checkin_date, checkin_time, response_date,
    response_time, response_day_diff, response_hour_diff" . ($approved == 0 ? "" : ", appointment_date, appointment_time, attention_day_diff, attention_hour_diff");

$values = "UUID(), '$PK_UUID', '$FK_EPS', '$FK_Ips', '$FK_Range', '$FK_Diagnosis', '$dni', '$name',
    '$lastname', '$contactype', '$approved', '$sentby', '$statuseps', '$callsnumber', '$comment', '$username',
    '$username', '$exhibitNine', '$exhibitTen', '$sendTo', '$commentOut', '$checkInDate', '$checkInTime', '$commentDate',
    '$commentTime', $responseDayDiff, '$responseTimeDiff'" . ($approved == 0 ? "" : ", '$appointmentdate', '$appointmenttime', $attetionDayDiff, '$attetionTimeDiff'");

$sql    = "INSERT INTO priorities ($cols) VALUES ($values)";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

mysqli_close($conn);

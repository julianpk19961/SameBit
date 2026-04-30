<?php
require_once 'setup.php';

$PK_UUID = isset($_POST["pk_uuid"]) ? trim($_POST["pk_uuid"]) : '';
$active  = isset($_POST["z_xone"])  ? intval($_POST["z_xone"]) : 0;

if (empty($PK_UUID) || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $PK_UUID)) {
    echo json_encode(['icon' => 'error', 'title' => 'Error', 'text' => 'ID invalido'], JSON_OUT);
    $conn->close();
    exit;
}

$stmt = $conn->prepare("SELECT COUNT(*) AS TOTAL FROM kardex WHERE medicine_id = ?");
$stmt->bind_param("s", $PK_UUID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total = intval($row['TOTAL']);
$stmt->close();

if ($total == 0) {
    $stmt = $conn->prepare("DELETE FROM medicines WHERE id = ?");
    $stmt->bind_param("s", $PK_UUID);
    $result = $stmt->execute();
    $stmt->close();
    $icon   = 'error';
    $title  = 'Registro Eliminado';
    $text   = 'No se encontraron movimientos en el kardex, el producto fue eliminado satisfactoriamente';
} else {
    $activeNew = $active == 1 ? 0 : 1;
    $accion    = $activeNew == 1 ? 'activado' : 'inactivado';
    $stmt = $conn->prepare("UPDATE medicines SET active = ? WHERE id = ?");
    $stmt->bind_param("is", $activeNew, $PK_UUID);
    $result = $stmt->execute();
    $stmt->close();
    $icon      = 'warning';
    $title     = 'Registro Actualizado';
    $text      = 'El registro fue ' . $accion . ' correctamente';
}

if (!$result) {
    echo json_encode(['icon' => 'error', 'title' => 'Error', 'text' => 'Query Error: ' . mysqli_error($conn)], JSON_OUT);
} else {
    echo json_encode(['icon' => $icon, 'title' => $title, 'text' => $text], JSON_OUT);
}

$conn->close();

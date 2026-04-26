<?php
include 'config.php';

$sql    = "SELECT id, name, nit FROM entities WHERE entity_type_id = (SELECT id FROM entity_types WHERE name = 'EPS') ORDER BY name";
$result = mysqli_query($conn, $sql);
$count  = mysqli_num_rows($result);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

if ($count > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $name = mb_convert_encoding($row['name'], 'ISO-8859-1', 'UTF-8');
        $json[] = array(
            'pk_uuid' => $row['id'],
            'name'    => mb_strtoupper($name, 'UTF-8'),
            'nit'     => $row['nit']
        );
    }
    echo json_encode($json);
} else {
    echo '[]';
}

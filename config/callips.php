<?php
include 'config.php';

$sql    = "SELECT id, name, nit FROM entities WHERE entity_type_id = (SELECT id FROM entity_types WHERE name = 'IPS') ORDER BY name";
$result = mysqli_query($conn, $sql);
$count  = mysqli_num_rows($result);

if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

if ($count > 0) {
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'pk_uuid' => $row['id'],
            'name'    => strtoupper(utf8_encode($row['name'])),
            'nit'     => $row['nit']
        );
    }
    echo json_encode($json);
} else {
    echo '[]';
}

<?php

include 'config.php';

$Dni = $_POST['dni'];

$sql = "SELECT  KP_UUID AS UUID, CONCAT(Name0,' ',LastName0) as PACIENTE,Name0 as NOMBRE,LastName0 as APELLIDO,
dni as DOC_NUMBER,documentType as DOC_TYPE,FK_Eps as EPS,FK_Range as CLASIFICACION 

FROM patients 
WHERE dni LIKE '%$Dni%' 
ORDER BY dni DESC";

$result = mysqli_query($conn, $sql);



if (!$result) {
    die('Query Error' . mysqli_error($conn));
}

$resultCount = mysqli_num_rows($result);

if ($resultCount > 0) {

    while ($data = mysqli_fetch_assoc($result)) {

        //en caso de tener problemas con la ñ y carácteres latam
        // $array['data'][] = array_map("utf8_encode",$data);

        $array['data'][] = $data;

        //   $data_array[] = array(
        //     $data->KP_UUID,
        //     $data->Dni,
        //     $data->Name0 . ' ' . $data->LastName0,
        //     );

    }
} else {
    $array['data'] = ['error' => 'no se encontraron registros'];
}

echo json_encode($array);
mysqli_close($conn);

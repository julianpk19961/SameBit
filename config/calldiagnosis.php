<?php
     
    // Datos de conexión
    include 'config.php';
    // Consultar datos diagnosticos y contarlos
    $sql = "SELECT  KP_UUID,Codigo,Observation FROM diagnosis ORDER BY Codigo";
    $result = mysqli_query($conn,$sql);
    $count = mysqli_num_rows($result);

    if(!$result){
        die('Query Error'. mysqli_error($conn));
    }


    if ($count>0){
        // Capturar los valores en un array de Json
        $json = array();
        while($row = mysqli_fetch_array($result)) {
            $Observation = utf8_encode( $row['Observation'] ); 
            $json[] = array (
                'KP_UUID' => $row['KP_UUID'],
                'Codigo' => $row['Codigo'],
                'Observation' => $Observation
            );
        }
        
        // Respuesta en formato Json
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }
    else{
        // Error por falta de registros
        $result = "No hay resultados";       
    }
?>
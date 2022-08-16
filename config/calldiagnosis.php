<?php
    // header("Content-Type: text/html; charset=iso-8859-1"); 

    include 'config.php';
    $sql = "SELECT  KP_UUID,Codigo,Observation FROM diagnosis ORDER BY Codigo";
    $result = mysqli_query($conn,$sql);
    $count = mysqli_num_rows($result);

    if(!$result){
        die('Query Error'. mysqli_error($conn));
    }


    if ($count>0){

        $json = array();
        while($row = mysqli_fetch_array($result)) {
            $json[] = array (
                'KP_UUID' => $row['KP_UUID'],
                'Codigo' => $row['Codigo'],
                // 'Observation' => $row['Observation']
            );
        }
        
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }
    else{
        $result = "No hay resultados";       
    }

    

?>
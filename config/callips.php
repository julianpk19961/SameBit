<?php
    // header("Content-Type: text/html; charset=iso-8859-1"); 

    include 'config.php';
    $sql = "SELECT  PK_UUID,Name0,Nit FROM entities WHERE FK_Type = '959df8c4-1a40-11ed-8aff-846993530662' ORDER BY Name0";
    $result = mysqli_query($conn,$sql);
    $count = mysqli_num_rows($result);

    if(!$result){
        die('Query Error'. mysqli_error($conn));
    }

    if ($count>0){

        $json = array();
        while($row = mysqli_fetch_array($result)) {
            $name = utf8_encode( $row['Name0'] );  
            $json[] = array (
                'pk_uuid' => $row['PK_UUID'],
                'name' => strtoupper($name),
                'nit' => $row['Nit']
            );
        }
        
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }
    else{
        $result = "No hay resultados";       
    }

?>
<?php

    include 'config.php';
    $Dni = $_POST['dni'];
    
    $sql = "SELECT  KP_UUID,Name0,LastName0,Dni FROM patients WHERE Dni LIKE '$Dni%' ORDER BY Dni DESC";
    $result = mysqli_query($conn,$sql);
    
    if (!$result){
        die('Query Error'. mysqli_error($conn));
    }

    $resultCount = mysqli_num_rows($result);

    if ($resultCount > 0 ){
        $json = array();
        while ($row = mysqli_fetch_array($result)){
        $json[] = array(
            'PK_UUID' => $row['KP_UUID'],
            'Name' => $row['Name0'],
            'LastName' => $row['LastName0'],
            'dni' => $row['Dni']
        );
        }
        $jsonstring = json_encode($json);
    }else{
        $jsonstring = 'error';
    }

    echo $jsonstring;
?>

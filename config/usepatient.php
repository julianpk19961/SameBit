<?php

    include 'config.php';

    if(isset($_POST['PK_UUID'])){
        $PK_UUID = $_POST['PK_UUID'];
           
        $sql = "SELECT KP_UUID,Name0,LastName0,Dni,documentType,FK_Eps,FK_Range FROM patients WHERE KP_UUID = '$PK_UUID' ";
        $result = mysqli_query($conn,$sql);
        if(!$result){
            die('Query Error'. mysqli_error($conn));
        }

        $json = array();
        while ( $row = mysqli_fetch_array($result) ){
            $json[] = array (
                'PK_UUID' => $row['KP_UUID'],
                'name' => $row['Name0'],
                'lastname' => $row['LastName0'],
                'dni' => $row['Dni'],
                'documentType' => $row['documentType'],
                'eps' => $row['FK_Eps'],
                'range' => $row['FK_Range']
            );
        }
        $jsonString = json_encode($json[0]);
        echo $jsonString;
    }

?>
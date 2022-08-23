<?php

    // Iniciar.
    include 'config.php';
    $Dni = $_POST['dni'];
    
    $sql = "SELECT  b.commentdate,b.commenttime,b.createdUser,b.comment0 FROM bitpriorities b INNER JOIN patients p ON p.KP_UUID = b.FK_Patient WHERE p.dni = '$Dni' ORDER BY b.commentdate ASC, b.commenttime";
    $result = mysqli_query($conn,$sql);
    
    if (!$result){
        die('Query Error'. mysqli_error($conn));
    }

    $resultCount = mysqli_num_rows($result);

    if ($resultCount > 0 ){
        $json = array();
        while ($row = mysqli_fetch_array($result)){
        $Observation = utf8_encode( $row['comment0'] ); 
        $json[] = array(
            'commentdate' => $row['commentdate'],
            'commenttime' => $row['commenttime'],
            'createdUser' => $row['createdUser'],
            'comment0' => $Observation
        );
        }
        $jsonstring = json_encode($json);
    }else{
        $jsonstring = 'error';
    }

    echo $jsonstring;
    
?>
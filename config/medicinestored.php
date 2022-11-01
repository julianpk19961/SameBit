<?php

include 'config.php';

 $name = isset($_POST["name"])?$_POST["name"]:'';
 $reference = isset($_POST["reference"])?$_POST["reference"]:'';
 $observation = isset($_POST["observation"])?$_POST["observation"]:'';

 $sql = "INSERT INTO medicines (nombre,referencia,observacion) 
 VALUES ( '$name','$reference','$observation') ";


$result = mysqli_query($conn,$sql);
if ($result){
    //     error
        $result = 'Query Error'. mysqli_error($conn);
}

echo $result;

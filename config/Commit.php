<?php
// #Conexión
include('config.php');

// date_default_timezone_set('America/Bogota');

#Captura Variables
$PK_UUID = isset($_POST["pk_uuid"])?$_POST["pk_uuid"]:1;
$documenttype = isset($_POST["documenttype"])?$_POST["documenttype"]:'';
$dni = isset($_POST["dni"])?$_POST["dni"]:'';
$name = isset($_POST["name"])?$_POST["name"]:'';
$lastname = isset($_POST["lastname"])?$_POST["lastname"]:'';
$contacttype = isset($_POST["lastname"])?$_POST["lastname"]:'';
$CommentDate = isset($_POST["CommentDate"])?$_POST["CommentDate"]:'';
$CommentTime = isset($_POST["CommentTime"])?$_POST["CommentTime"]:'';
$approved = isset($_POST["approved"])?$_POST["approved"]:'';
$AtentionDate = isset($_POST["AtentionDate"])?$_POST["AtentionDate"]:'';
$AtentionTime = isset($_POST["AtentionTime"])?$_POST["AtentionTime"]:'';
$Observation = isset($_POST["Observation"])?$_POST["Observation"]:''; 
$Eps = isset($_POST["Eps"])?$_POST["Eps"]:''; 
$Ips = isset($_POST["Ips"])?$_POST["Ips"]:''; 
$SentBy = isset($_POST["SentBy"])?$_POST["SentBy"]:'';
$EpsStatus = isset($_POST["EpsStatus"])?$_POST["EpsStatus"]:'';
$EpsClassification = isset($_POST["EpsClassification"])?$_POST["EpsClassification"]:'';
$CallNumber = isset($_POST["CallNumber"])?$_POST["CallNumber"]:'';
$diagnosis = isset($_POST["diagnosis"])?$_POST["diagnosis"]:'';


#CREAR//ACTUALIZAR REGISTRO
if($PK_UUID == 1){

    $sql = "INSERT INTO Patients (KP_UUID,Dni,documentType,Name0,LastName0,Fk_Eps,FK_Range) 
            VALUES ( UUID(),'$dni','$documenttype','$name','$lastname','$Eps',$EpsClassification ) ";
    $new = 1;

}else{

    $sql = "UPDATE Patients SET Dni = '$dni' , documentType = '$documenttype' , Name0 = '$name', LastName0 = '$lastname', Fk_Eps = '$Eps', FK_Range = '$EpsClassification' 
    WHERE Dni = '$dni' "; 
    
}

$result = mysqli_query($conn,$sql_ptn);
if (!$result){
    die('Query Error'. mysqli_error($conn));
}







echo $result ;




// if(mysqli_query($conn, $Sql)){
//        echo "Records added successfully.";
//    } else{
//        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
//    }
   
?>


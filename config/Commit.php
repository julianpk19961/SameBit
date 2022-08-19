<?php
// #Conexión
include('config.php');

// date_default_timezone_set('America/Bogota');

#Captura Variables
$PK_UUID = isset($_POST["pk_uuid"])?$_POST["pk_uuid"]:1;
$documenttype = isset($_POST["documenttype"])?$_POST["documenttype"]:1;
$dni = isset($_POST["documenttype"])?$_POST["documenttype"]:1;
$name = isset($_POST["name"])?$_POST["name"]:1;
$lastname = isset($_POST["lastname"])?$_POST["lastname"]:1;
$contacttype = isset($_POST["lastname"])?$_POST["lastname"]:1;
$CommentDate = isset($_POST["CommentDate"])?$_POST["CommentDate"]:1;
$CommentTime = isset($_POST["CommentTime"])?$_POST["CommentTime"]:1;
$approved = isset($_POST["approved"])?$_POST["approved"]:1;
$AtentionDate = isset($_POST["AtentionDate"])?$_POST["AtentionDate"]:1;
$AtentionTime = isset($_POST["AtentionTime"])?$_POST["AtentionTime"]:1;  
$Observation0 = isset($_POST["Observation0"])?$_POST["Observation0"]:1;
$Observation1 = isset($_POST["Observation1"])?$_POST["Observation1"]:1; 
$Eps = isset($_POST["Eps"])?$_POST["Eps"]:1; 
$Ips = isset($_POST["Ips"])?$_POST["Ips"]:1; 
$SentBy = isset($_POST["SentBy"])?$_POST["SentBy"]:1;
$EpsStatus = isset($_POST["EpsStatus"])?$_POST["EpsStatus"]:1;
$EpsClassification = isset($_POST["EpsClassification"])?$_POST["EpsClassification"]:1;
$CallNumber = isset($_POST["CallNumber"])?$_POST["CallNumber"]:1;
$diagnosis = isset($_POST["diagnosis"])?$_POST["diagnosis"]:1;

if($PK_UUID == 1){

    //Crear el paciente si no existe.
    $sql_patient = "INSERT INTO patients (KP_UUID)";



    $Sql = "INSERT INTO BitPriorities (PK_UUID,FK_Patient,FK_EPS,FK_Range,FK_Ips,FK_Diagnosis,Dni,Name0,LastName0,SentBy,Comment0,CommentDate,AppointmentDate,Accept,StatusEps,AppointmentHour,CommentHour,CallsNumber,imageb64,z_xOne,created,updated,createdUser,updatedUser) 
    VALUES ( UUID(), '$Dni', '$Eps', '$Range', '$Ips', '$Diagnosis', '$Dni','$Name','$LastName0', '$SentBy','$Comment','$CommentDate','$AppointmentDate','$Accept','$StatusEps','$AppointmentHour','$CommentHour','$CallsNumber','1',1,'$Time0' ,'','Julian','')"; 
}else{
    $Sql = "INSERT INTO BitPriorities (PK_UUID,FK_Patient,FK_EPS,FK_Range,FK_Ips,FK_Diagnosis,Dni,Name0,LastName0,SentBy,Comment0,CommentDate,AppointmentDate,Accept,StatusEps,AppointmentHour,CommentHour,CallsNumber,imageb64,z_xOne,created,updated,createdUser,updatedUser) 
    VALUES ('$PK_UUID ', '$Dni', '$Eps', '$Range', '$Ips', '$Diagnosis', '$Dni','$Name','$LastName0', '$SentBy','$Comment','$CommentDate','$AppointmentDate','$Accept','$StatusEps','$AppointmentHour','$CommentHour','$CallsNumber','1',1,'$Time0' ,'','Julian','')"; 
}
echo $Sql;




// if(mysqli_query($conn, $Sql)){
//        echo "Records added successfully.";
//    } else{
//        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
//    }
   
?>


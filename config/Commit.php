<?php
#Conexión
include('config.php');

#Captura Variables
$Dni = isset($_POST["Dni"])?$_POST["Dni"]:'';
$Name = isset($_POST["Name"])?$_POST["Name"]:'';
$LastName = isset($_POST["LastName"])?$POST["LastName"]:'';
$Phone = isset($_POST["Phone"])?$POST["Phone"]:'';
$CommentDate = isset($_POST["CommentDate"])?$_POST["CommentDate"]:'';
$CommentHour = isset($_POST["CommentHour"])?$_POST["CommentHour"]:'';
$Diagnosis = isset($_POST["Diagnosis"])?$_POST["Diagnosis"]:'';
$Accept = isset($_POST["Accept"])?$_POST["Accept"]:'';
$Eps = isset($_POST["Eps"])?$_POST["Eps"]:'';
$Range = isset($_POST["Range"])?$_POST["Range"]:'';
$StatusEps = isset($_POST["StatusEps"])?$_POST["StatusEps"]:'';
$SentBy = isset($_POST["SentBy"])?$_POST["SentBy"]:'';
$Ips = isset($_POST["Ips"])?$_POST["Ips"]:'';
$AppointmentDate = isset($_POST["AppointmentDate"])?$_POST["AppointmentDate"]:'';
$AppointmentHour = isset($_POST["AppointmentHour"])?$_POST["AppointmentHour"]:'';
$CallsNumber = isset($_POST["CallsNumber"])?$_POST["CallsNumber"]:'';
$Comment = isset($_POST["Comment"])?$_POST["Comment"]:'';
$TimeStamp = strtotime("now");

#Insert
$Sql = "INSERT INTO BitPriorities ('PK_UUID','FK_Patient','FK_EPS','FK_Range','FK_Ips','FK_Diagnosis','Dni','Name0','LastName0','SentBy','Comment0','CommentDate','AppointmentDate','Accept','StatusEps','AppointmentHour','CommentHour','CallsNumber','imageb64','z_xOne','created','updated','createdUser','updatedUser') VALUES ('1', $Dni, $Eps, $Range, $Ips, $Diagnosis, $Dni,$Name,$LastName, $SentBy,$Comment,$CommentDate,$AppointmentDate,$Accept,$StatusEps,$AppointmentHour,$CommentHour,$CallsNumber,'1',1,$TimeStamp ,'','Julian','')"; 

if(mysqli_query($conn, $Sql)){
       echo "Records added successfully.";
   } else{
       echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
   }
 ?>


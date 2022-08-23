<?php
    // #Conexión
    include('config.php');

    // Establecer zona horaria.
    date_default_timezone_set('America/Bogota');

    // Captura Variables.
    $PK_UUID = isset($_POST["pk_uuid"])?$_POST["pk_uuid"]:1;
    $FK_EPS = isset($_POST["Eps"])?$_POST["Eps"]:''; 
    $FK_Ips = isset($_POST["Ips"])?$_POST["Ips"]:''; 
    $FK_Range = isset($_POST["EpsClassification"])?$_POST["EpsClassification"]:'';
    $FK_Diagnosis = isset($_POST["diagnosis"])?$_POST["diagnosis"]:'';
    $dni = isset($_POST["dni"])?$_POST["dni"]:'';
    $documenttype = isset($_POST["documenttype"])?$_POST["documenttype"]:'';
    $name = isset($_POST["name"])?$_POST["name"]:'';
    $lastname = isset($_POST["lastname"])?$_POST["lastname"]:'';
    $contactype = isset($_POST["contacttype"])?$_POST["contacttype"]:'';
    $commentdate = isset($_POST["CommentDate"])?$_POST["CommentDate"]:'';
    $commenttime = isset($_POST["CommentTime"])?$_POST["CommentTime"]:'';
    $approved = isset($_POST["approved"])?$_POST["approved"]:'';
    $appointmentdate = isset($_POST["AtentionDate"])?$_POST["AtentionDate"]:'';
    $appointmenttime = isset($_POST["AtentionTime"])?$_POST["AtentionTime"]:'';
    $comment = isset($_POST["Observation"])?$_POST["Observation"]:''; 
    $sentby = isset($_POST["SentBy"])?$_POST["SentBy"]:'';
    $statuseps = isset($_POST["EpsStatus"])?$_POST["EpsStatus"]:'';
    $callsnumber = isset($_POST["CallNumber"])?$_POST["CallNumber"]:'';

    // Crear-Actualizar registros.
    if($PK_UUID == 1){
        // Crear el registro.
        $sql = "INSERT INTO Patients (KP_UUID,Dni,documentType,Name0,LastName0,Fk_Eps,FK_Range) 
                VALUES ( UUID(),'$dni','$documenttype','$name','$lastname','$FK_EPS','$FK_Range' ) ";
    }else{
        // Actualizar el registro.
        $sql = "UPDATE Patients SET Dni = '$dni' , documentType = '$documenttype' , Name0 = '$name', LastName0 = '$lastname', Fk_Eps = '$FK_EPS', FK_Range = '$FK_Range' 
        WHERE Dni = '$dni' "; 
    }

    // Salida por error.
    $result = mysqli_query($conn,$sql);
    if (!$result){
        // error
        die('Query Error'. mysqli_error($conn));
    }

    // Consultar el UUID del documento ingresado.
    $sql = "SELECT KP_UUID FROM Patients WHERE dni='$dni'";
    $result = mysqli_query($conn,$sql);

    if (!$result){
        die('Query Error'.mysqli_error($conn));
    }
    while($row = mysqli_fetch_array($result)){
        // Capturar uuid
        $PK_UUID = $row['KP_UUID'];
    }

    // Inserción de datos a la tabla de bitacora de prioridades.
    $sql = "INSERT INTO bitpriorities (PK_UUID,FK_Patient,FK_EPS,FK_Ips,FK_Range,FK_Diagnosis,dni,name0,lastname,contactype,commentdate,commenttime,approved,appointmentdate,appointmenttime,sentby,statuseps,callsnumber,comment0 )
    VALUES ( UUID(),'$PK_UUID','$FK_EPS','$FK_Ips','$FK_Range','$FK_Diagnosis','$dni','$name','$lastname','$contactype','$commentdate','$commenttime','$approved','$appointmentdate','$appointmenttime','$sentby','$statuseps','$callsnumber','$comment') ";
    $result = mysqli_query($conn,$sql);

    if (!$result){
        // Error.
        die('Query Error'. mysqli_error($conn));
    }

    echo "Registro enviado satisfactoriamente";

?>


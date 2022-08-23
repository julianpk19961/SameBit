<?php
#Conexión
include('config.php');

#Verificar Credenciales

$name0 = isset($_POST["username"])?$_POST["username"]:'';
$pass0 = isset($_POST["password"])?$_POST["password"]:'';

$result = mysqli_query($conn,"SELECT * FROM bitusers WHERE NickName = '".$name0."' AND Password0 = '".$pass0."'");

while($row = mysqli_fetch_array($result)){
    mysqli_close($conn);
    $Exist = $row['KP_UUID'];
}

if (empty($Exist)){
    $Exist = 0 ;
}else{
    session_start();
    $_SESSION['name'] = $name0 ;
}

echo $Exist;
?>
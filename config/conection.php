<?php
#Conexión
include('config.php');

#Verificar Credenciales

$name0 = isset($_POST["username"])?$_POST["username"]:'';
$pass0 = isset($_POST["password"])?$_POST["password"]:'';

$Query = mysqli_query($conn,"SELECT * FROM bitusers WHERE NickName = '".$name0."' AND Password0 = '".$pass0."'");
$nr = mysqli_num_rows($Query);

if ($nr == 1)
{
    //echo "Bienvenido: ";
    session_start();
    $_SESSION['logged_in_user_id'] = '1';
    $_SESSION['name'] = 'Julian';
    echo '1';
}
elseif ($nr == 0)
{
    echo '0';
    $_SESSION['name'] = 'Julian';
   // echo "Usuario o clave invalida";
}


?>
<?php

session_start();
if(isset($_POST["Accion"])){
    if(isset($_POST["Accion"])=='login'){
        login();
    };
};


function login()
{
    #Conexión
    include('config.php');

    try {
       #Verificar Credenciales
        $name0 = isset($_POST["name"])?$_POST["name"]:'';
        $pass0 = isset($_POST["pass"])?$_POST["pass"]:'';
        $md5pass = md5($pass0);
        $json = array();
        $nombreUser = '';

        $sql = "SELECT Name0,Name1,LastName0,LastName1 FROM bitusers WHERE NickName = '".$name0."' AND Password0 = '".$md5pass."'";
        $result = mysqli_query($conn,$sql);
        $rowsresult = $result->num_rows;
            if ($rowsresult > 0){
                $row = $result->fetch_array(MYSQLI_NUM);
                $userFullName=$row[0]." ".$row[2];
                $_SESSION['usuario'] =  $userFullName;
                $nombreUser = $_SESSION['usuario'];
                $urlDashboard = "http://192.168.1.22/samebit/pages/dashboard.php";
                // echo '<pre>';
                // var_dump($SqlRow);
                // exit();
            }
            
        $message=['Title'=>'Éxito','Mensaje'=>'Conexión exitosa','Tipo'=>'success','nombreusuario'=>$nombreUser,'url'=>$urlDashboard];
    } catch (\Throwable $th) {
        $message=['Title'=>'Error','Mensaje'=>'Conexión fallida','Tipo'=>'error'];
    }

    
    $message = json_encode($message);
    echo $message;
}

?>
<?php

session_start();
if (isset($_POST["Accion"])) {
    if (isset($_POST["Accion"]) == 'login') {
        login();
    };
};


function login()
{
    #Conexión
    include('config.php');

    try {
        #Verificar Credenciales
        $name0 = isset($_POST["name"]) ? $_POST["name"] : '';
        $pass0 = isset($_POST["pass"]) ? $_POST["pass"] : '';
        $md5pass = md5($pass0);
        $json = array();
        $nombreUsuario = '';
        $url = '';

        $sql = "SELECT Name0,Name1,LastName0,LastName1,fk_privilegeSet FROM bitusers WHERE NickName = '" . $name0 . "' AND Password0 = '" . $md5pass . "'";
        $result = mysqli_query($conn, $sql);
        $rowsresult = $result->num_rows;
        if ($rowsresult > 0) {

            $row = $result->fetch_array(MYSQLI_NUM);
            $userFullName = $row[0] . " " . $row[2];
            $privilegeSet = $row[4];

            if ($privilegeSet != 'root' && $privilegeSet != 'prioritaria') {
                $file = 'medicines_l.php';
            } else {
                $file = 'dashboard.php';
            }


            $_SESSION['usuario'] =  $userFullName;

            // Parametros Sweet Alert
            $titulo = 'Éxito';
            $subMensaje = 'Conexión Exitosa';
            $tipo = 'success';
            $nombreUsuario = $_SESSION['usuario'];
            $url = "http://localhost/samebit/pages/".$file;
        } else {

            $titulo = 'Error';
            $subMensaje = 'Usuario no encontrado';
            $tipo = 'error';
            $nombreUsuario = '';
            $url = '';
            $privilegeSet = '';
        }
    } catch (\Throwable $th) {

        $titulo = 'Error';
        $subMensaje = 'Usuario no encontrado';
        $tipo = 'error';
        $nombreUsuario = '';
        $url = '';
        $privilegeSet = '';
    }


    $message = ['Title' => $titulo, 'Mensaje' => $subMensaje, 'Tipo' => $tipo, 'nombreusuario' => $nombreUsuario, 'url' => $url, 'privilegeSet' => $privilegeSet];
    $message = json_encode($message);
    echo $message;
}

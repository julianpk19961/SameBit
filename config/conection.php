<?php
include('config.php');
session_start();

header('Content-Type: text/html; charset=UTF-8');

if (isset($_POST["Accion"]) && $_POST["Accion"] == 'login') {
    login();
}

function login()
{
    global $conn;

    $name0   = isset($_POST["name"]) ? $_POST["name"] : '';
    $pass0   = isset($_POST["pass"]) ? $_POST["pass"] : '';
    $md5pass = md5($pass0);

    $sql    = "SELECT id, first_name, last_name, privilege FROM users WHERE username = '" . $name0 . "' AND password = '" . $md5pass . "'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row          = mysqli_fetch_array($result, MYSQLI_NUM);
        $userFullName = $row[1] . ' ' . $row[2];
        $privilegeSet = $row[3];

        $file = ($privilegeSet === 'root' || $privilegeSet === 'admin')
            ? 'dashboard.php'
            : 'medicines_l.php';

        $_SESSION['id']      = $row[0];
        $_SESSION['usuario'] = $userFullName;

        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $base     = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/';
        $url      = $base . 'pages/' . $file;

        $titulo       = 'Éxito';
        $subMensaje   = 'Conexión Exitosa';
        $tipo         = 'success';
        $nombreUsuario = $userFullName;
    } else {
        $titulo        = 'Error';
        $subMensaje    = 'Usuario no encontrado';
        $tipo          = 'error';
        $nombreUsuario = '';
        $url           = '';
        $privilegeSet  = '';
    }

    $message = [
        'Title'         => $titulo,
        'Mensaje'       => $subMensaje,
        'Tipo'          => $tipo,
        'nombreusuario' => $nombreUsuario,
        'url'           => $url,
        'privilegeSet'  => $privilegeSet,
    ];
    echo json_encode($message);
}

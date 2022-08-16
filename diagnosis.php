<!DOCTYPE html>
<html lang="en">
<head>
   
<!-- <?php 
include '../config/setup.php';
session_start();

$nombre = isset($_POST[$_SESSION['name']])?$_SESSION['name']:'';
$id =isset($_POST[$_SESSION['id']])?$_SESSION['id']:'';
?> -->

<head>
  <title>Diagnosticos</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="./css/main.css" rel="stylesheet" type="text/css">
  <!-- <link href="./css/Login.css" rel="stylesheet" type="text/css"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Barra de menu -->
</head>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
        <a class="navbar-brand" href="#">
            <img src='./img/SameinLogo.png' style="width: 36%; margin-top: -8%;" class="" alt="Responsive image"> 
        </a>
    </div>

    <ul class="nav navbar-nav mr-auto">
      <li class="active"><a href="#">Inicio</a></li>
      <li><a href="#">Pacientes</a></li>
      <li><a href="#">Entidades</a></li>
      <li><a href="#">Reportes</a></li>
      <li>  
        <a href="./config/logout.php"><i class="fa fa-power-off"></i> Cerrar Sesión</a>
      </li>
    </ul>
  </div>
</nav>


<body>
    AQUI ES EL BODY
</body>
</html>
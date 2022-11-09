 <?php
  include '../config/setup.php';
  session_start();
  ?>

 <!DOCTYPE html>
 <html>

 <head>
   <title><?php echo $title; ?></title>
   <meta name="viewport" content="width=device-width, initial-scale=1" http-equiv="content-type" content="text/html;" charset="UTF-8">

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
   <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
   <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css">1
   <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

   <?php
    if (empty($_SESSION['usuario'])) {
      // No hacer nada si viene vacio
    } else {
      $nombre = isset($_POST[$_SESSION['usuario']]) ? $_SESSION['usuario'] : '1';
    }
    //  Identificar pagina
    $link = $_SERVER['PHP_SELF'];
    $link_array = explode('/', $link);
    $page = end($link_array);

    // Cargar estilo dependiendo de página
    if ($page == 'login.php') { ?>
     <link href="../css/login.css" rel="stylesheet" type="text/css">
   <?php } else { ?>
     <link href="../css/pacientes.css" rel="stylesheet" type="text/css">
   <?php } ?>
 </head>

 <body>

   <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
   <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"></script>

   <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

  
 </body>

 </html>
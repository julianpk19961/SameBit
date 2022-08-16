
<!-- <?php 
include '../config/setup.php';
session_start();

$nombre = isset($_POST[$_SESSION['name']])?$_SESSION['name']:'';
$id =isset($_POST[$_SESSION['id']])?$_SESSION['id']:'';
?> -->

<head>
  <title><?php echo $title;?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <?php 
     $link = $_SERVER['PHP_SELF'];
     $link_array = explode('/',$link);
     $page = end($link_array);
     if($page =='login.php'){ ?>
        <link href="../css/login.css" rel="stylesheet" type="text/css">
    <?php } else{?> 
        <link href="../css/login.css" rel="stylesheet" type="text/css">
        <link href="../css/main.css" rel="stylesheet" type="text/css">
   <?php }?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

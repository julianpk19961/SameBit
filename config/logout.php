<?php
include 'setup.php';

if (isset($_SESSION)) 
{
   // return session_destroy();
    $_SESSION['id'] = '';
    header("Location: $index");
    die();
}


?>


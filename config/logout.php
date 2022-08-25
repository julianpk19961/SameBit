<?php
include 'setup.php';

if (isset($_SESSION)) 
{
   // return session_destroy();
    $_SESSION['id'] = '';
    $_SESSION['usuario'] = '';
    die();
}
header("Location: $index");
?>


<?php
include 'setup.php';

if (isset($_SESSION)) 
{
   // return session_destroy();
    $_SESSION['id'] = '';
    die();
}
header("Location: $index");
?>


<?php
include 'setup.php';

if (isset($_SESSION)) 
{
   // return session_destroy();
    $_SESSION['logged_in_user_id'] = '0';
    header("Location: $index");
    die();
}


?>


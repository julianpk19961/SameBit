<?php
//session_destroy();
header('Location:http://localhost/SAME_BIT/login.html');
//print_r(session_destroy());
if (isset($_SESSION)) {
    return session_destroy();
    $_SESSION['logged_in_user_id'] = '0';
}
return false;
?>


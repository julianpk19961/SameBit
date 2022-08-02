<?php
if (isset($_SESSION)) {
    return session_destroy();
    $_SESSION['logged_in_user_id'] = '0';
    header('Location:http://localhost/SAME_BIT/login.html');
}
return false;
?>


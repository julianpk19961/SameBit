<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$appName = 'bit-medical';

$index = 'http://localhost:8081/';
$url = '/pages/login.php';
$urldashboard = $index.'pages/dashboard.php';
$title = $appName;

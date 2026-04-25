<?php
include './config/setup.php';

if (isset($_SESSION["id"])) {
  header("Location: $urldashboard");
  die();
} else {
  header("Location: $url");
  die();
}

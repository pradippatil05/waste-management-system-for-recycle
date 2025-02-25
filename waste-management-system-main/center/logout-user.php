<?php
session_start();
session_unset();
session_destroy();
header('location: center-login.php');
?>
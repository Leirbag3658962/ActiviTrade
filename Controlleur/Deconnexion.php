<?php
session_start();
if(!isset($_SESSION['user'])) {
    header('Location: /Vue/Pages/LogIn.php');
    exit;
}
unset($_SESSION['user']);

header('Location: /Vue/Pages/LogIn.php');
?>
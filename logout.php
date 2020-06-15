<?php
session_start();

setcookie('remember', NULL, -1);

unset($_SESSION['auth']);


$_SESSION['flash']['positive'] = "Vous êtes maintenant déconnectés";


header('Location: login.php');
exit();
<?php

session_start();
$_SESSION = [];
session_unset();
session_destroy();


setcookie('id_user' . '', time() - 3600);
setcookie('key', '', time() - 3600);
setcookie('username', '', time() - 3600);
setcookie('halo_username', '', time() - 3600);




header("Location: login.php");
exit;

<?php 
require 'functions.php';
global $conn;
$sql = "select * from user";
$res = $conn ->query($sql);

if($res->num_rows) {
    $users -> $res -> fetch_all(MYSQLI_ASSOC); 
} else {
    echo 'no result';
}

var_dump($users);

<?php
include 'functions.php';

// Query to retrieve data from turnamen and turnamen_tim tables
$query = "SELECT * from user";

// Execute the query
$teams = query($query);

// Check if the query was successful
if ($teams) {
    // If successful, display the team names
    foreach ($teams as $team) {
        echo $team['nama_turnamen'] . "<br>";
    }
} else {
    // If the query failed, display an error message
    echo "Gagal mengambil data tim";
}
?>

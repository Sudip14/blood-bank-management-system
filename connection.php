<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "blood_bank";

// Establish database connection
$con = mysqli_connect($host, $user, $password, $db);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

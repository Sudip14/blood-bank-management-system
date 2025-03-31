<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "blood_bank";

$con = mysqli_connect($host, $user, $password, $db);
if ($con) {
    echo "";
} else {
    echo "DB connection failed";
}

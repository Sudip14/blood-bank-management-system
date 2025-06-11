<?php
session_start();
$_SESSION['user_id'] = 1; // Test with valid ID
include 'notification.php';

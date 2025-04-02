<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $blood_group = htmlspecialchars(trim($_POST['blood_group']));
    $location = htmlspecialchars(trim($_POST['location']));

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($blood_group) || empty($location)) {
        header("Location: register.php?status=error&message=All fields are required!");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?status=error&message=Invalid email format!");
        exit();
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $checkQuery = "SELECT * FROM `doners` WHERE `email` = ?";
    $stmt = $con->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: register.php?status=error&message=This email is already registered! Try another email.");
        exit();
    }

    // Insert new user
    $stmt = $con->prepare("INSERT INTO `doners`(`name`, `email`, `password`, `blood_group`, `location`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $hashed_password, $blood_group, $location);

    if ($stmt->execute()) {
        header("Location: login.php?status=success&message=Registered successfully! You can now login.");
        exit();
    } else {
        header("Location: register.php?status=error&message=Error occurred while registering. Try again.");
        exit();
    }

    $stmt->close();
}
?>

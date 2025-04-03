<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $blood_group = htmlspecialchars(strip_tags(trim($_POST['blood_group'])));
    $location = htmlspecialchars(strip_tags(trim($_POST['location'])));
    $gender = isset($_POST['gender']) ? $_POST['gender'] : null;  // Capture gender


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

    // Check if email already exists (Fix table name issue)
    $stmt = $con->prepare("SELECT email FROM `doners` WHERE `email` = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: register.php?status=error&message=This email is already registered! Try another email.");
        exit();
    }

    $stmt->close();

    // Validate age
    $age = intval($_POST['age']);
    if ($age < 18) {
        header("Location: register.php?status=error&message=You must be at least 18 years old to register.");
        exit();
    }
    

    // Insert new user
    $stmt = $con->prepare("INSERT INTO `doners`(`name`, `age`, `gender`, `email`, `password`, `blood_group`, `location`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssss", $name, $age, $gender, $email, $hashed_password, $blood_group, $location);
    

    if ($stmt->execute()) {
       // header("Location: login.php?status=success&message=Registered successfully! You can now log in.");
        echo "<div style='color: green; font-weight: bold;'>Register successful! Redirecting...</div>";
        echo "<script>setTimeout(() => { window.location.href='login.php'; }, 2000);</script>";
        exit();
    } else {
        header("Location: register.php?status=error&message=Error occurred while registering. Try again.");
        exit();
    }

    $stmt->close();
    $con->close();
}
?>

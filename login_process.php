<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=EmptyFields");
        exit();
    }

    // Fetch user from database
    $stmt = $con->prepare("SELECT id, name, email, password, blood_group, location FROM doners WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $email, $hashed_password, $blood_group, $location);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_blood_group'] = $blood_group;
            $_SESSION['user_location'] = $location;

            header("Location: login.php?login=success");
            exit();
        } else {
            header("Location: login.php?error=InvalidPassword");
            exit();
        }
    } else {
        header("Location: login.php?error=UserNotFound");
        exit();
    }

    $stmt->close();
}
?>

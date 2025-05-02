<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "❌ Please fill in both email and password.";
        header("Location: login.php");
        exit();
    }

    // Fetch user from the database
    $stmt = $con->prepare("SELECT id, name, email, password, contact, blood_group, location FROM doners WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $db_email, $hashed_password, $db_contact, $blood_group, $location);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Secure session
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $db_email;
            $_SESSION['user_contact'] = $db_contact;
            $_SESSION['user_blood_group'] = $blood_group;
            $_SESSION['user_location'] = $location;

            $stmt->close();
            $con->close();
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "❌ Incorrect password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "❌ User not found with that email.";
        header("Location: login.php");
        exit();
    }
}
?>

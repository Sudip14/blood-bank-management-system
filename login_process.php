<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Check for empty fields
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "❌ Please fill in both email and password.";
        header("Location: login.php");
        exit();
    }

    // Fetch user securely from the database
    $stmt = $con->prepare("SELECT id, name, email, password, contact, blood_group, location FROM doners WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $db_email, $hashed_password, $db_contact, $blood_group, $location);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Regenerate session ID for security
            session_regenerate_id(true);

            // Set consistent session variables
            $_SESSION['doners_id'] = $id;
            $_SESSION['user_id'] = $id; // optional, keep for compatibility
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $db_email;
            $_SESSION['user_contact'] = $db_contact;
            $_SESSION['user_blood_group'] = $blood_group;
            $_SESSION['user_location'] = $location;

            // Close statement and connection
            $stmt->close();
            $con->close();

            // Redirect to appointment page
            header("Location: book_appointment.php");
            exit();
        } else {
            $_SESSION['error'] = "❌ Incorrect password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "❌ No user found with that email.";
        header("Location: login.php");
        exit();
    }
} else {
    // Redirect if accessed without POST
    header("Location: login.php");
    exit();
}
?>

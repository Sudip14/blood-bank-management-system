<?php
include 'connection.php';

if (isset($_POST['submit'])) {
    $username   = $_POST['username'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name  = $_POST['full_name'];
    $contact    = $_POST['contact'];
    $role       = 'admin';

    // Check if email already exists
    $check = $con->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        header("Location: admin_register.php?message=Email already registered&status=error");
        exit;
    }

    $stmt = $con->prepare("INSERT INTO users (username, email, password, full_name, contact, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $password, $full_name, $contact, $role);

    if ($stmt->execute()) {
        header("Location: admin_register.php?message=Admin registered successfully&status=success");
    } else {
        header("Location: admin_register.php?message=Registration failed&status=error");
    }
}
?>

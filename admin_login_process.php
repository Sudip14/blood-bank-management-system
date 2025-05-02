<?php
include 'connection.php';

session_start();
$admin_id = $_SESSION['admin_id']; // this should be set at login


if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            header("Location: admin_dashboard.php");
            exit;
        } else {
            header("Location: admin_login.php?message=Incorrect password");
            exit;
        }
    } else {
        header("Location: admin_login.php?message=Admin not found");
        exit;
    }
} else {
    header("Location: admin_login.php?message=Please fill in all fields");
    exit;
}
?>

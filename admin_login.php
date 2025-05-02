<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | BloodCare</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* (keep your existing CSS) */
        body {
            background: linear-gradient(to right, #fff 0%, #fef6f6 100%);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 6rem auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(209, 0, 0, 0.1);
        }

        .login-container h2 {
            text-align: center;
            color: #d10000;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: #555;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #d10000;
        }

        .login-btn {
            width: 100%;
            padding: 0.8rem;
            background: #d10000;
            border: none;
            color: white;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background: #a30000;
        }

        .back-home {
            display: block;
            margin-top: 1.5rem;
            text-align: center;
            color: #777;
            text-decoration: none;
        }

        .back-home:hover {
            text-decoration: underline;
            color: #d10000;
        }

        .message {
            color: red;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Admin Login</h2>

        <?php if (isset($_GET['message'])): ?>
            <div class="message"><?= htmlspecialchars($_GET['message']) ?></div>
        <?php endif; ?>

        <form action="admin_login_process.php" method="post">
            <div class="form-group">
                <label for="admin-username">Username</label>
                <input type="text" id="admin-username" name="username" required>
            </div>

            <div class="form-group">
                <label for="admin-password">Password</label>
                <input type="password" id="admin-password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>
        <a href="index.php" class="back-home"><i class="fas fa-home"></i> Back to Home</a>
    </div>

</body>
</html>

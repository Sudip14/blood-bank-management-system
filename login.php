<?php include 'connection.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background: #f8f8f8;
        }

        header {
            background: red;
            color: white;
            padding: 20px;
            font-size: 24px;
        }

        .container {
            padding: 20px;
        }

        .section {
            margin: 20px auto;
            padding: 20px;
            width: 80%;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
            background: white;
        }

        .btn {
            background: red;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn:hover {
            background: darkred;
        }

        form {
            text-align: left;
            display: inline-block;
            width: 100%;
            max-width: 400px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .forgot-password {
            text-align: center;
            margin-top: 10px;
        }

        .forgot-password a {
            color: red;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>Login</header>

    <div class="container">
        <div class="section">
            <h2>Login to Your Account</h2>

            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == "InvalidPassword") {
                    echo "<div style='color: red; font-weight: bold;'>Incorrect password. Please try again.</div>";
                } elseif ($_GET['error'] == "UserNotFound") {
                    echo "<div style='color: red; font-weight: bold;'>User not found. Please register first.</div>";
                }
            }

            if (isset($_GET['login']) && $_GET['login'] == "success") {
                echo "<div style='color: green; font-weight: bold;'>Login successful! Redirecting...</div>";
                echo "<script>setTimeout(() => { window.location.href='dashboard.php'; }, 2000);</script>";
            }
            ?>

            <form action="login_process.php" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="forgot-password">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>
        </div>
        <div class="section" style="margin-top: 10px;">
            <h2>Don't have an account?</h2>
            <a href="register.php" class="btn">Register Now</a>
        </div>
    </div>

</body>
</html>

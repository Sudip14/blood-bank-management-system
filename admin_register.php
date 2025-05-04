<?php include 'connection.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background: #f8f8f8;
        }

        header {
            background: #d10000;
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
            background: #d10000;
            color: white;
            padding: 10px;
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

        .error {
            color: red;
            font-weight: bold;
        }

        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header>Admin Registration</header>

    <div class="container">
        <div class="section">
            <h2>Register as an Admin</h2>

            <!-- Show error/success messages -->
            <?php if (isset($_GET['message'])): ?>
                <div class="<?= $_GET['status'] == 'error' ? 'error' : 'success' ?>">
                    <?= htmlspecialchars($_GET['message']) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="admin_register_process.php">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="text" name="contact" placeholder="Phone Number" pattern="\d{10}" required>
                <button type="submit" name="submit" class="btn">Register Admin</button>
            </form>
        </div>
    </div>
</body>
</html>

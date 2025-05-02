<?php include 'connection.php'; // Include your database connection here


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST["email"]));

    // Check if the email exists in the users table
    $stmt = $conn->prepare("SELECT id FROM doners WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email exists
        $confirmation = "Hold tight! A password reset link is flying to your email ($email).";
    } else {
        // Email not found
        $error = "Email not found. Please <a href='register.php'>register</a> first.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
            max-width: 500px;
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
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .message {
            margin-top: 15px;
            font-weight: bold;
            color: green;
        }

        .error {
            margin-top: 15px;
            font-weight: bold;
            color: red;
        }

        a {
            color: blue;
        }
    </style>
</head>
<body>
    <header>Forgot Password</header>

    <div class="container">
        <div class="section">
            <h2>Reset Your Password</h2>
            <form method="POST" action="">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <button type="submit" class="btn">Reset Password</button>
            </form>

            <?php if ($confirmation): ?>
                <p class="message"><?= $confirmation ?></p>
            <?php elseif ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


<?php include 'connection.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Registration</title>
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
    <header>Donor Registration</header>

    <div class="container">
        <div class="section">
            <h2>Register as a Donor</h2>

            <!-- Show error/success messages -->
            <?php if (isset($_GET['message'])): ?>
                <div class="<?= $_GET['status'] == 'error' ? 'error' : 'success' ?>">
                    <?= htmlspecialchars($_GET['message']) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="register_process.php">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="number" name="age" placeholder="Age" required>
                <div style="display: flex; gap: 10px; align-items: center;">
    <label>Gender:</label>
    <label><input type="radio" name="gender" value="Male" required> Male</label>
    <label><input type="radio" name="gender" value="Female" required> Female</label>
    <label><input type="radio" name="gender" value="Other" required> Other</label>
</div>



                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="blood_group" placeholder="Blood Group (e.g., A+)" required>
                <input type="text" name="location" placeholder="Location" required>
                <button type="submit" name="submit" class="btn">Register</button>
            </form>

        </div>
        <div class="section" style="margin-top: 10px;">
            <h2>Already have an account?</h2>
            <a href="login.php" class="btn">Login Now</a>
        </div>
    </div>
</body>

</html>

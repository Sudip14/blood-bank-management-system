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
    </style>
</head>

<body>
    <header>Donor Registration</header>

    <div class="container">
        <div class="section">
            <h2>Register as a Donor</h2>
            <form id="registerForm" method="post" action="">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="blood_group" placeholder="Blood Group (e.g., A+)" required>
                <input type="text" name="location" placeholder="Location" required>
                <button type="submit" name="submit" class="btn">Register</button>
            </form>
        </div>
    </div>

</body>

</html>

<?php
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Securely hash the password
    $blood_group = $_POST['blood_group'];
    $location = $_POST['location'];

       // Check if email already exists
       $checkQuery = "SELECT * FROM `doners` WHERE `email` = ?";
       $stmt = $con->prepare($checkQuery);
       $stmt->bind_param("s", $email);
       $stmt->execute();
       $stmt->store_result();
       if ($stmt->num_rows > 0) {
        echo "<script>alert('This email is already registered! Try another email.');</script>";
    } else {

    $stmt = $con->prepare("INSERT INTO `doners`(`name`, `email`, `password`, `blood_group`, `location`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $password, $blood_group, $location);

    if ($stmt->execute()) {
        echo "<script>alert('Registered successfully!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Error occurred while registering. Try again.');</script>";
    }
    }
    $stmt->close();
}
?>

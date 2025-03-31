<?php
include 'connection.php'; // Include your database connection here
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank System</title>
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

        .qr-section img,
        .section img {
            width: 100%;
            border-radius: 10px;
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
    </style>
</head>

<body>
    <header>Blood Bank Management System</header>

    <div class="container">
        <!-- Donor Registration Section -->
        <div class="section">
            <h2>Donor Registration</h2>
            <img src="donor.jpg" alt="Donor Registration">
            <p>Register as a blood donor and help save lives.</p>
            <a href="register.php"><button class="btn">Register Now</button></a>
        </div>

        <!-- Find Blood Section -->
        <div class="section">
            <h2>Find Blood</h2>
            <img src="find-blood.jpg" alt="Find Blood">
            <p>Search for available blood donors near you.</p>
            <a href="search.php"><button class="btn">Search Now</button></a>
        </div>

        <!-- QR Code Section -->
        <div class="section qr-section">
            <h2>Scan QR to Register or Request</h2>
            <img src="qr-code.png" alt="QR Code">
            <p>Use this QR code to register as a donor or request blood instantly.</p>
        </div>

        <!-- Login Section -->
        <div class="section">
            <h2>Login</h2>
            <img src="login.jpg" alt="Login">
            <p>Login to access your donor dashboard.</p>
            <a href="login.php"><button class="btn">Login Now</button></a>
        </div>
    </div>
</body>

</html>
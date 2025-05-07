<?php
session_start();
include 'connection.php'; // Your DB connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $blood_group = $_POST['blood_group'];
    $location = $_POST['location'];
    $phone = $_POST['contact'];
    $email = $_POST['email'];


    // Update query
    $sql = "UPDATE doners SET name=?, blood_group=?, location=?, contact=?, email=? WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssi", $name, $blood_group, $location, $phone, $email, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name; // update session name
        echo "<script>alert('Profile updated successfully'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Update failed');</script>";
    }

    $stmt->close();
}

// Fetch current user data
$sql = "SELECT name, blood_group, location, contact, email FROM doners WHERE id=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <style>
        body {
            font-family: Arial;
            background: #f9f9f9;
            padding: 2rem;
        }
        form {
            background: white;
            padding: 2rem;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 1rem;
        }
        input, select {
            width: 100%;
            padding: 0.6rem;
            margin-top: 0.4rem;
        }
        button {
            margin-top: 1.5rem;
            padding: 0.7rem 1.2rem;
            background: #d10000;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #a70000;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Update Your Profile</h2>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="name" required value="<?= htmlspecialchars($user['name']) ?>">

        <label>Blood Group:</label>
        <select name="blood_group" required>
            <?php
            $groups = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];
            foreach ($groups as $group) {
                $selected = ($user['blood_group'] === $group) ? 'selected' : '';
                echo "<option value='$group' $selected>$group</option>";
            }
            ?>
        </select>

        <label>Location:</label>
        <input type="text" name="location" required value="<?= htmlspecialchars($user['location']) ?>">

        <label>Phone:</label>
        <input type="text" name="contact" required value="<?= htmlspecialchars($user['contact']) ?>">

        <label>Email (Gmail):</label>
<input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">


        <button type="submit">Update Profile</button>
    </form>
</body>
</html>

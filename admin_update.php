<?php
include 'connection.php';
session_start();

// Make sure admin is logged in
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    // Fetch admin details
    $admin_query = mysqli_query($con, "SELECT * FROM users WHERE id = '$admin_id'");
    $users = mysqli_fetch_assoc($admin_query);

    // Handle form submission to update details
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_username = mysqli_real_escape_string($con, $_POST['username']);
        $new_email = mysqli_real_escape_string($con, $_POST['email']);
        $new_contact = mysqli_real_escape_string($con, $_POST['contact']);

        // Update user information in the database
        $update_query = "UPDATE users SET username = '$new_username', email = '$new_email', contact = '$new_contact' WHERE id = '$admin_id'";

        if (mysqli_query($con, $update_query)) {
            echo "<p>Profile updated successfully.</p>";
            // Redirect to dashboard or profile page after update
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<p>Error updating profile: " . mysqli_error($con) . "</p>";
        }
    }
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile | BloodCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: #fef6f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #d10000;
            color: #fff;
            position: fixed;
            height: 100%;
            padding-top: 2rem;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.8rem;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar ul li {
            padding: 15px 20px;
            transition: 0.3s;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1rem;
            display: block;
        }
        .sidebar ul li:hover {
            background: #a50000;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            flex: 1;
        }
        .form-container {
            background: #fff;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(209, 0, 0, 0.05);
            border-radius: 10px;
            max-width: 600px;
            margin: 2rem auto;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #d10000;
        }
        .form-container label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .form-container input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container button {
            width: 100%;
            padding: 1rem;
            background: #d10000;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
        }
        .form-container button:hover {
            background: #a50000;
        }

        /* Footer */
        footer {
            background: #d10000;
            color: white;
            text-align: center;
            padding: 2rem;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>BloodCare Admin</h2>
        <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_donors.php"><i class="fas fa-tachometer-alt"></i> Manage Donors</a></li>
            <li><a href="blood_inventory.php"><i class="fas fa-tint"></i> Blood Inventory</a></li>
            <li><a href="finds_requests.php"><i class="fas fa-search"></i> Find Requests</a></li>
            <li><a href="admin_update.php"><i class="fas fa-cog"></i> Settings</a></li>
          <li><a href="admin_register.php"> <i class="fa-solid fa-user">+</i>Add New Admin</a></li>
            <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="form-container">
            <h2>Update Your Profile</h2>
            <form method="POST" action="admin_update.php">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($users['username']); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($users['email']); ?>" required>

                <label for="contact">Contact Number</label>
                <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($users['contact']); ?>" required>

                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 BloodCare Admin Panel. All rights reserved.</p>
    </footer>
</body>
</html>

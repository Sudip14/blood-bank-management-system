<?php
include 'connection.php';
session_start(); // Start session if not already started

// Make sure admin_id is stored in session
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
    // Fetch admin details
    $admin_query = mysqli_query($con, "SELECT * FROM users WHERE id = '$admin_id'");
    $users = mysqli_fetch_assoc($admin_query);
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

?>



<h1>Welcome Admin!</h1>
<!-- <a href="logout.php">Logout</a> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | BloodCare</title>
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
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .dashboard-header h1 {
            font-size: 2rem;
            color: #d10000;
        }
        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
        }
        /* .admin-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        } */
        span a:hover {
    color: red;
}


        /* Cards Section */
        .cards {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }
        .card-button {
            background: white;
            flex: 1;
            min-width: 250px;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(209, 0, 0, 0.05);
            text-decoration: none;
            color: inherit;
            transition: transform 0.3s ease, background 0.3s;
            text-align: center;
        }
        .card-button:hover {
            transform: translateY(-5px);
            background: #ffe5e5;
        }
        .card-button h3 {
            font-size: 1.5rem;
            color: #d10000;
            margin-bottom: 1rem;
        }
        .card-button p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Footer */
        footer {
            margin-left: 0px;
            background: #d10000;
            color: white;
            text-align: center;
            padding: 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .main-content, footer {
                margin-left: 200px;
            }
        }
        @media (max-width: 576px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                display: flex;
                justify-content: center;
            }
            .main-content, footer {
                margin-left: 0;
            }
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
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <div class="admin-profile">
                <!-- <img src="admin_profile.jpg" alt="Admin Profile Picture"> -->
                <span>
    <a href="admin_update.php" style="text-decoration: none; color: #333;">
        <i class="fa-solid fa-user" style="margin-right: 5px;"></i>
        <?php echo htmlspecialchars($users['username']); ?>
    </a>
</span>
                </div>
        </div>

        <!-- Cards Section -->
        <?php

// Count active donors

$donor_result = mysqli_query($con, "SELECT COUNT(*) AS total_donors FROM doners");
$donor_data = mysqli_fetch_assoc($donor_result);
$total_donors = $donor_data['total_donors'];

$request_result = mysqli_query($con, "SELECT COUNT(*) AS pending_requests FROM blood_requests WHERE status = 'pending'");
$request_data = mysqli_fetch_assoc($request_result);
$pending_requests = $request_data['pending_requests'];


// Count total units in inventory
$inventory_result = mysqli_query($con, "SELECT SUM(units_available) AS total_units FROM inventory");
$inventory_data = mysqli_fetch_assoc($inventory_result);
$total_units = $inventory_data['total_units'] ?? 0;

// // Count emergency alerts
// $alert_result = mysqli_query($con, "SELECT COUNT(*) AS emergency_alerts FROM emergencies WHERE status = 'new'");
// $alert_data = mysqli_fetch_assoc($alert_result);
// $emergency_alerts = $alert_data['emergency_alerts'];
// ?>
<div class="cards">
    <a href="manage_donors.php" class="card-button">
        <h3>Active Donors</h3>
        <p><?php echo $total_donors; ?> Registered</p>
    </a>
    <a href="finds_requests.php" class="card-button">
        <h3>Blood Requests</h3>
        <p><?php echo $pending_requests; ?> Pending</p>
    </a>
    <a href="blood_inventory.php" class="card-button">
        <h3>Inventory</h3>
        <p><?php echo $total_units; ?> Units Available</p>
    </a>
    <!-- <a href="emergency_alerts.php" class="card-button">
        <h3>Emergency Alerts</h3>
        <p><?php echo $emergency_alerts; ?> New</p>
    </a> -->
</div>


    <!-- Footer -->
    <footer>
        <p>&copy; 2025 BloodCare Admin Panel. All rights reserved.</p>
    </footer>

</body>
</html>

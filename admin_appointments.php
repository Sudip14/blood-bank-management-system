<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Update appointment status securely
if (isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $con->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<p style='color:red;'>Error updating status: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}

// Fetch all appointments with donor info
$result = mysqli_query($con, "
    SELECT a.*, d.name, d.contact 
    FROM appointments a 
    JOIN doners d ON a.doners_id = d.id 
    ORDER BY a.appointment_date
");

if (!$result) {
    die("<p style='color:red;'>Error fetching appointments: " . htmlspecialchars(mysqli_error($con)) . "</p>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Appointments | BloodCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            color: #333;
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
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #34495e;
            color: #fff;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        form {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        select {
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            background-color: #fff;
            font-size: 14px;
        }

        input[type="submit"] {
            padding: 6px 12px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #27ae60;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .main-content {
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
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <h2>BloodCare Admin</h2>
    <ul>
        <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="manage_donors.php"><i class="fas fa-user"></i> Manage Donors</a></li>
        <li><a href="blood_inventory.php"><i class="fas fa-tint"></i> Blood Inventory</a></li>
        <li><a href="finds_requests.php"><i class="fas fa-search"></i> Find Requests</a></li>
        <li><a href="admin_update.php"><i class="fas fa-cog"></i> Settings</a></li>
        <li><a href="admin_appointments.php"><i class="fas fa-cog"></i> Appointment Management</a></li>
        <li><a href="admin_register.php"><i class="fas fa-user-plus"></i> Add Admin</a></li>
        <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2>Manage Appointments</h2>
    <table>
        <tr>
            <th>Donor</th>
            <th>Contact</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Location</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['contact']) ?></td>
            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
            <td><?= htmlspecialchars($row['appointment_time']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <select name="status">
                        <?php
                        $statuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];
                        foreach ($statuses as $s) {
                            $selected = ($row['status'] === $s) ? 'selected' : '';
                            echo "<option value=\"$s\" $selected>$s</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" name="update_status" value="Update">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
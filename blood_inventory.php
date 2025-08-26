<?php
include 'connection.php';

// Handle form submissions
if (isset($_POST['add_units'])) {
    $blood_group = $_POST['blood_group'];
    $units = (int)$_POST['units'];

    $stmt = $con->prepare("UPDATE inventory SET units_available = units_available + ? WHERE blood_group = ?");
    $stmt->bind_param("is", $units, $blood_group);
    $stmt->execute();

    $log = $con->prepare("INSERT INTO inventory_log (blood_group, change_type, units_changed) VALUES (?, 'add', ?)");
    $log->bind_param("si", $blood_group, $units);
    $log->execute();
}

if (isset($_POST['remove_units'])) {
    $blood_group = $_POST['blood_group'];
    $units = (int)$_POST['units'];

    $stmt = $con->prepare("UPDATE inventory SET units_available = units_available - ? WHERE blood_group = ?");
    $stmt->bind_param("is", $units, $blood_group);
    $stmt->execute();

    $log = $con->prepare("INSERT INTO inventory_log (blood_group, change_type, units_changed) VALUES (?, 'remove', ?)");
    $log->bind_param("si", $blood_group, $units);
    $log->execute();
}

// Fetch Inventory
$inventory = $con->query("SELECT * FROM inventory");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Inventory Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: #f8f8f8;
        }

        .sidebar {
            width: 250px;
            background: #d10000;
            color: white;
            height: 100vh;
            position: fixed;
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
            color: white;
            text-decoration: none;
            display: block;
            font-size: 1.1rem;
        }

        .sidebar ul li:hover,
        .sidebar ul li.active {
            background: #a50000;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
        }

        h1 {
            background: red;
            color: white;
            padding: 20px;
            margin-bottom: 1rem;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px gray;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background: red;
            color: white;
        }

        tr.low-stock {
            background: #ffe6e6;
        }

        form {
            margin: 30px auto;
            max-width: 400px;
        }

        input, select, button {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background: red;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: darkred;
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
            }

            .main-content {
                margin-left: 0;
                width: 100%;
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
        <li><a href="manage_donors.php"><i class="fas fa-user"></i> Manage Donors</a></li>
        <li class="active"><a href="blood_inventory.php"><i class="fas fa-tint"></i> Blood Inventory</a></li>
        <li><a href="finds_requests.php"><i class="fas fa-search"></i> Find Requests</a></li>
        <li><a href="admin_update.php"><i class="fas fa-cog"></i> Settings</a></li>   
        <li><a href="admin_appointments.php"><i class="fas fa-cog"></i> Appointment Management</a></li> 
        <li><a href="admin_register.php"> <i class="fa-solid fa-user">+</i>Add New Admin</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h1>Blood Bank Inventory Dashboard</h1>

    <table>
        <tr>
            <th>Blood Group</th>
            <th>Units Available</th>
        </tr>

        <?php while($row = $inventory->fetch_assoc()): ?>
            <tr class="<?= ($row['units_available'] < 5) ? 'low-stock' : '' ?>">
                <td><?= htmlspecialchars($row['blood_group']) ?></td>
                <td><?= htmlspecialchars($row['units_available']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2>Update Inventory</h2>

    <form method="post">
        <select name="blood_group" required>
            <option value="">Select Blood Group</option>
            <?php
            $groups = ["A+", "A-", "B+", "B-", "O+", "O-", "AB+", "AB-"];
            foreach ($groups as $group) {
                echo "<option value=\"$group\">$group</option>";
            }
            ?>
        </select>
        <input type="number" name="units" placeholder="Units" min="1" required>
        <button type="submit" name="add_units">➕ Add Units</button>
        <button type="submit" name="remove_units">➖ Remove Units</button>
    </form>
</div>

</body>
</html>

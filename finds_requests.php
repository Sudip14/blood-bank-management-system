<?php
include 'connection.php';

// Get filter inputs
$blood_group = isset($_POST['blood_group']) ? trim($_POST['blood_group']) : '';
$city = isset($_POST['city']) ? trim($_POST['city']) : '';

// Step 1: Fetch all data without filtering/sorting
$result = $con->query("SELECT * FROM blood_requests");
$requests = $result->fetch_all(MYSQLI_ASSOC);

// Step 2: Filter using algorithm (array_filter)
$filtered = array_filter($requests, function($row) use ($blood_group, $city) {
    return 
        (!$blood_group || $row['blood_group'] === $blood_group) &&
        (!$city || stripos($row['city'], $city) !== false);
});

// Step 3: Sort using algorithm (usort)
usort($filtered, function($a, $b) {
    return strtotime($b['request_time']) - strtotime($a['request_time']); // latest first
});

// Step 4: Mark urgent requests
foreach($filtered as $key => $row){
    $filtered[$key]['class'] = ($row['units_requested'] > 3) ? 'urgent' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Requests | BloodCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #fef6f6; min-height: 100vh; display: flex; }
        .sidebar { width: 250px; background: #d10000; color: #fff; position: fixed; height: 100%; padding-top: 2rem; }
        .sidebar h2 { text-align: center; margin-bottom: 2rem; font-size: 1.8rem; }
        .sidebar ul { list-style: none; }
        .sidebar ul li { padding: 15px 20px; transition: 0.3s; }
        .sidebar ul li a { color: #fff; text-decoration: none; font-size: 1.1rem; display: block; }
        .sidebar ul li:hover, .sidebar ul li.active { background: #a50000; }
        .main-content { margin-left: 250px; padding: 2rem; flex: 1; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .header h1 { font-size: 2rem; color: #d10000; }
        .search-section { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(209,0,0,0.05); margin-bottom: 2rem; }
        .search-section form { display: flex; flex-wrap: wrap; gap: 1rem; }
        .search-section input, .search-section select, .search-section button { padding: 0.8rem; border: 1px solid #ccc; border-radius: 5px; flex: 1; min-width: 200px; }
        .search-section button { background: #d10000; color: white; border: none; cursor: pointer; transition: background 0.3s; }
        .search-section button:hover { background: #a50000; }
        .results { background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(209,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        table th, table td { padding: 0.8rem; border-bottom: 1px solid #eee; text-align: left; }
        table th { background: #d10000; color: white; }
        .urgent { background: #ffe6e6; } /* highlight urgent */
        footer { margin-left: 250px; background: #d10000; color: white; text-align: center; padding: 1rem; margin-top: auto; }
        @media (max-width: 768px) { .sidebar { width: 200px; } .main-content, footer { margin-left: 200px; } }
        @media (max-width: 576px) { .sidebar { position: relative; width: 100%; height: auto; } .main-content, footer { margin-left: 0; } }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>BloodCare Admin</h2>
        <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_donors.php"><i class="fas fa-users"></i> Manage Donors</a></li>
            <li><a href="blood_inventory.php"><i class="fas fa-tint"></i> Blood Inventory</a></li>
            <li class="active"><a href="find_requests.php"><i class="fas fa-search"></i> Find Requests</a></li>
            <li><a href="admin_update.php"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="admin_appointments.php"><i class="fas fa-calendar-check"></i> Appointment Management</a></li>
            <li><a href="admin_register.php"><i class="fa-solid fa-user">+</i> Add New Admin</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Find Blood Requests</h1>
        </div>

        <div class="search-section">
            <form action="" method="post">
                <select name="blood_group">
                    <option value="">Select Blood Group</option>
                    <?php
                    $groups = ["A+", "A-", "B+", "B-", "O+", "O-", "AB+", "AB-"];
                    foreach ($groups as $group) {
                        $selected = ($blood_group == $group) ? 'selected' : '';
                        echo "<option value=\"$group\" $selected>$group</option>";
                    }
                    ?>
                </select>
                <input type="text" name="city" placeholder="Enter City" value="<?= htmlspecialchars($city) ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="results">
            <h2>Search Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Blood Group</th>
                        <th>Units Requested</th>
                        <th>City</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($filtered)): ?>
                        <?php foreach ($filtered as $row): ?>
                            <tr class="<?= $row['class'] ?>">
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['blood_group']) ?></td>
                                <td><?= htmlspecialchars($row['units_requested']) ?></td>
                                <td><?= htmlspecialchars($row['city']) ?></td>
                                <td><?= htmlspecialchars($row['contact']) ?></td>
                                <td><?= htmlspecialchars($row['user_email']) ?></td>
                                <td><?= htmlspecialchars($row['request_time']) ?></td>
                                <td><?= htmlspecialchars($row['status']) ?></td>
                                <td>
                                    <form action="update_status.php" method="post">
                                        <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                        <select name="status">
                                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Approved" <?= $row['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                                            <option value="Rejected" <?= $row['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                        <button type="submit">Update Status</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="9">No requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 BloodCare Admin Panel. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'connection.php';

$user_id = $_SESSION['user_id'];

$stmt = $con->prepare("SELECT * FROM blood_requests WHERE user_id = ?");
if (!$stmt) {
    die("Prepare failed: " . $con->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Blood Requests</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 0;
            margin: 0;
        }

        /* Navigation Bar */
        .navbar {
            background-color: #d60000;
            overflow: hidden;
            padding: 10px 20px;
        }

        .navbar a {
            float: left;
            color: white;
            text-align: center;
            padding: 12px 16px;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar a:hover {
            background-color: #b30000;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="my_requests.php">My Requests</a>
    <a href="donate.php">Donate Blood</a>
    <a href="logout.php" style="float:right;">Logout</a>
</div>

<h2>My Blood Requests</h2>

<table>
    <tr>
        <th>Blood Group</th>
        <th>Units Requested</th>
        <th>City</th>
        <th>Contact</th>
        <th>Date</th>
        <th>Status</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['blood_group']) ?></td>
                <td><?= htmlspecialchars($row['units_requested']) ?></td>
                <td><?= htmlspecialchars($row['city']) ?></td>
                <td><?= htmlspecialchars($row['contact']) ?></td>
                <td><?= htmlspecialchars($row['request_time']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="6">No blood requests found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
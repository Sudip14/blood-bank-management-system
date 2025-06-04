<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'connection.php';

$user_id = $_SESSION['user_id'];

$stmt = $con->prepare("SELECT * FROM blood_requests WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Blood Requests</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: red; color: white; }
        h2 { text-align: center; }
    </style>
</head>
<body>

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
</table>

</body>
</html>

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
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; text-align: center; margin: 0; }
        h1 { background: red; color: white; padding: 20px; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; background: white; box-shadow: 0 0 10px gray; }
        th, td { padding: 12px; border: 1px solid #ccc; }
        th { background: red; color: white; }
        tr.low-stock { background: #ffe6e6; }
        form { margin: 20px auto; width: 300px; }
        input, select, button { padding: 10px; margin: 10px 0; width: 100%; border-radius: 5px; border: 1px solid #ccc; }
        button { background: red; color: white; border: none; cursor: pointer; }
        button:hover { background: darkred; }
    </style>
</head>
<body>

    <h1>Blood Bank Inventory Dashboard</h1>

    <table>
        <tr>
            <th>Blood Group</th>
            <th>Units Available</th>
        </tr>

        <?php while($row = $inventory->fetch_assoc()): ?>
            <tr class="<?= ($row['units_available'] < 5) ? 'low-stock' : '' ?>">
                <td><?= $row['blood_group'] ?></td>
                <td><?= $row['units_available'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2>Update Inventory</h2>

    <form method="post">
        <select name="blood_group" required>
            <option value="">Select Blood Group</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
        </select>
        <input type="number" name="units" placeholder="Units" min="1" required>

        <button type="submit" name="add_units">➕ Add Units</button>
        <button type="submit" name="remove_units">➖ Remove Units</button>
    </form>

</body>
</html>

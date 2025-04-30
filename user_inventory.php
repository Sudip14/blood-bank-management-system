<?php
include 'connection.php';

// Initialize variables
$search = "";
$message = "";

// Handle blood request form submission
if (isset($_POST['request_blood'])) {
    $requested_blood_group = $_POST['blood_group'];
    $requested_units = intval($_POST['units']);

    if ($requested_units <= 0) {
        $message = "❌ Units must be greater than zero.";
    } else {
        // Check inventory
        $stmt = $con->prepare("SELECT units_available FROM inventory WHERE blood_group = ?");
        $stmt->bind_param("s", $requested_blood_group);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['units_available'] >= $requested_units) {
                // Insert into blood_requests table
                $stmt = $con->prepare("INSERT INTO blood_requests (blood_group, units_requested) VALUES (?, ?)");
                $stmt->bind_param("si", $requested_blood_group, $requested_units);
                $stmt->execute();

                // Update inventory
                $stmt = $con->prepare("UPDATE inventory SET units_available = units_available - ? WHERE blood_group = ?");
                $stmt->bind_param("is", $requested_units, $requested_blood_group);
                $stmt->execute();

                $message = "✅ Blood request submitted successfully!";
            } else {
                $message = "❌ Not enough units available.";
            }
        } else {
            $message = "❌ Blood group not found.";
        }
    }
}

// Handle search
if (isset($_POST['search'])) {
    $search = $_POST['search_text'];
    $stmt = $con->prepare("SELECT * FROM inventory WHERE blood_group LIKE ?");
    $likeSearch = "%$search%";
    $stmt->bind_param("s", $likeSearch);
    $stmt->execute();
    $inventory = $stmt->get_result();
} else {
    $inventory = $con->query("SELECT * FROM inventory");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Bank Inventory</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; text-align: center; margin: 0; }
        h1 { background: red; color: white; padding: 20px; }
        form { margin: 20px auto; width: 300px; }
        input, select, button { padding: 10px; width: 80%; margin: 10px 5%; border-radius: 5px; border: 1px solid #ccc; }
        button { background: red; color: white; border: none; cursor: pointer; }
        button:hover { background: darkred; }
        table { width: 90%; margin: 20px auto; border-collapse: collapse; background: white; box-shadow: 0 0 10px gray; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
        th { background: red; color: white; }
        tr.low-stock { background: #ffe6e6; }
        .message { color: green; font-weight: bold; }
    </style>
</head>
<body>

    <h1>Blood Bank Inventory</h1>

    <?php if (!empty($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <!-- Search Form -->
    <form method="post">
        <input type="text" name="search_text" placeholder="Search Blood Group..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" name="search">🔍 Search</button>
    </form>

    <!-- Blood Request Form -->
    <form method="post">
    <select name="blood_group" required>
    <option value="">Select Blood Group</option>
    <?php
    $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
    foreach ($bloodGroups as $group) {
        echo "<option value=\"$group\">$group</option>";
    }
    ?>
</select>

        <input type="number" name="units" placeholder="Enter Units" min="1" required>
        <button type="submit" name="request_blood">🩸 Request Blood</button>
    </form>

    <!-- Inventory Table -->
    <table>
    <tr>
        <th>Blood Group</th>
        <th>Units Available</th>
        <th>Action</th>
    </tr>

    <?php if ($inventory->num_rows > 0): ?>
        <?php while($row = $inventory->fetch_assoc()): ?>
            <tr class="<?= ($row['units_available'] < 5) ? 'low-stock' : '' ?>">
                <td><?= $row['blood_group'] ?></td>
                <td><?= $row['units_available'] ?></td>
                <td>
                    <?php if ($row['units_available'] > 0): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="blood_group" value="<?= $row['blood_group'] ?>">
                            <input type="hidden" name="units" value="1">
                            <button type="submit" name="request_blood">🩸 Request</button>
                        </form>
                    <?php else: ?>
                        <span style="color:red;">Out of Stock</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">No results found.</td>
        </tr>
    <?php endif; ?>
</table>


</body>
</html>

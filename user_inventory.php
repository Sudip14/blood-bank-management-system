<?php
include 'connection.php';

// Initialize search variable
$search = "";

// Handle request blood
if (isset($_POST['request_blood'])) {
    $requested_blood_group = $_POST['blood_group'];
    $requested_units = 1; // You can allow users to select units too if needed

    // Insert request into a table (you need to create a table `blood_requests`)
    $stmt = $con->prepare("INSERT INTO blood_requests (blood_group, units_requested) VALUES (?, ?)");
    $stmt->bind_param("si", $requested_blood_group, $requested_units);
    $stmt->execute();

    $message = "✅ Blood request submitted successfully!";
}

// Search blood
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
    <title>Available Blood Units</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; text-align: center; margin: 0; }
        h1 { background: red; color: white; padding: 20px; }
        form { margin: 20px auto; width: 300px; }
        input[type="text"], button { padding: 10px; width: 80%; margin: 10px 5%; border-radius: 5px; border: 1px solid #ccc; }
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

    <h1>Available Blood Units</h1>

    <?php if (!empty($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <!-- Search Form -->
    <form method="post">
        <input type="text" name="search_text" placeholder="Search Blood Group..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" name="search">🔍 Search</button>
    </form>

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
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="blood_group" value="<?= $row['blood_group'] ?>">
                            <button type="submit" name="request_blood">🩸 Request</button>
                        </form>
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

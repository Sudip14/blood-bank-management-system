<?php
include 'connection.php';
session_start();

// Initialize feedback message
$message = '';

// Handle update
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $blood_group = trim($_POST['blood_group']);
    $age = intval($_POST['age']);
    $contact = trim($_POST['contact']);
    $location = trim($_POST['location']);
    $times_donated = intval($_POST['times_donated']);
    $last_donation_date = !empty($_POST['last_donation_date']) ? $_POST['last_donation_date'] : NULL;

    $update_sql = "UPDATE doners SET name=?, blood_group=?, age=?, contact=?, location=?, times_donated=?, last_donation_date=? WHERE id=?";
    $stmt = $con->prepare($update_sql);
    $stmt->bind_param("ssissssi", $name, $blood_group, $age, $contact, $location, $times_donated, $last_donation_date, $id);

    if ($stmt->execute()) {
        $message = "Donor details updated successfully.";
    } else {
        $message = "Error updating donor details: " . $stmt->error;
    }
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $con->prepare("DELETE FROM doners WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Donor deleted successfully.";
    } else {
        $message = "Error deleting donor: " . $stmt->error;
    }
    header("Location: manage_donors.php?msg=" . urlencode($message));
    exit;
}

// Get donor for editing
$edit_donor = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $con->prepare("SELECT * FROM doners WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_donor = $result->fetch_assoc();
}

// Handle search safely using prepared statement
$search = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = trim($_GET['search']);
    $search_param = "%" . $search . "%";
    $stmt = $con->prepare("SELECT * FROM doners WHERE name LIKE ? OR blood_group LIKE ? OR location LIKE ?");
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $con->query("SELECT * FROM doners");
}

// Display message from redirect
if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Donors</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fef6f6;
            padding: 2rem;
        }
        h2 {
            color: #d10000;
            text-align: center;
            margin-bottom: 2rem;
        }
        .message {
            max-width: 600px;
            margin: 1rem auto;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            background-color: #28a745;
            text-align: center;
        }
        .error {
            background-color: #dc3545;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 8px rgba(209, 0, 0, 0.1);
        }
        th, td {
            padding: 1rem;
            border: 1px solid #ffdcdc;
            text-align: left;
        }
        th {
            background-color: #d10000;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #fff1f1;
        }
        .action-buttons a {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }
        .edit-btn {
            background: #007bff;
        }
        .delete-btn {
            background: #dc3545;
        }
        .search-form {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-form input {
            padding: 8px;
            width: 300px;
        }
        .search-form button {
            padding: 8px 16px;
            cursor: pointer;
        }
        input[type="date"], input[type="number"], input[type="text"] {
            padding: 5px;
            font-size: 14px;
        }
        input[type="number"] {
            width: 80px;
        }
        form.update-form button[type="submit"] {
            padding: 5px 10px;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        form.update-form a {
            padding: 5px 10px;
            background-color: #6c757d;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <h2>All Registered Donors</h2>

    <?php if (!empty($message)): ?>
        <div class="message <?= strpos($message, 'Error') === 0 ? 'error' : '' ?>">
            <?= $message; ?>
        </div>
    <?php endif; ?>

    <form method="GET" action="manage_donors.php" class="search-form">
        <input type="text" name="search" placeholder="Search by name, blood group or location" value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <a href="manage_donors.php"><button type="button">Reset</button></a>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Blood Group</th>
            <th>Age</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Location</th>
            <th>Times Donated</th>
            <th>Last Donation Date</th>
            <th>Actions</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php if ($edit_donor && $edit_donor['id'] == $row['id']): ?>
                    <tr>
                        <form method="POST" action="manage_donors.php" class="update-form">
                            <input type="hidden" name="id" value="<?= $edit_donor['id']; ?>">
                            <td><?= $edit_donor['id']; ?></td>
                            <td><input type="text" name="name" value="<?= htmlspecialchars($edit_donor['name']); ?>" required></td>
                            <td><input type="text" name="blood_group" value="<?= htmlspecialchars($edit_donor['blood_group']); ?>" required></td>
                            <td><input type="number" name="age" value="<?= htmlspecialchars($edit_donor['age']); ?>" required min="1"></td>
                            <td><?= htmlspecialchars($edit_donor['email']); ?></td>
                            <td><input type="text" name="contact" value="<?= htmlspecialchars($edit_donor['contact']); ?>" required></td>
                            <td><input type="text" name="location" value="<?= htmlspecialchars($edit_donor['location']); ?>" required></td>
                            <td><input type="number" name="times_donated" value="<?= htmlspecialchars($edit_donor['times_donated']); ?>" min="0"></td>
                            <td><input type="date" name="last_donation_date" value="<?= htmlspecialchars($edit_donor['last_donation_date']); ?>"></td>
                            <td>
                                <button type="submit" name="update">Update</button>
                                <a href="manage_donors.php">Cancel</a>
                            </td>
                        </form>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['blood_group']); ?></td>
                        <td><?= htmlspecialchars($row['age']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['contact']); ?></td>
                        <td><?= htmlspecialchars($row['location']); ?></td>
                        <td><?= htmlspecialchars($row['times_donated']); ?></td>
                        <td><?= $row['last_donation_date'] ? date('F j, Y', strtotime($row['last_donation_date'])) : 'N/A'; ?></td>
                        <td class="action-buttons">
                            <a href="manage_donors.php?edit_id=<?= $row['id']; ?>" class="edit-btn">Edit</a>
                            <a href="manage_donors.php?delete_id=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this donor?');">Delete</a>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="10">No donors found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

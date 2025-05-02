<?php
include 'connection.php';
session_start();

// Handle update if form is submitted
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $blood_group = $_POST['blood_group'];
    $age = intval($_POST['Age']);
    $contact = $_POST['contact'];
    $location = $_POST['location'];

    $update_sql = "UPDATE doners SET name=?, blood_group=?, age=?, contact=?, location=? WHERE id=?";
    $stmt = $con->prepare($update_sql);
    $stmt->bind_param("ssissi", $name, $blood_group, $age, $contact, $location, $id);
    $stmt->execute();

    header("Location: manage_donors.php");
    exit;
}

// If 'edit_id' is present, get donor info
$edit_donor = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $edit_result = $con->query("SELECT * FROM doners WHERE id = $edit_id");
    $edit_donor = $edit_result->fetch_assoc();
}

// Handle delete if requested
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM doners WHERE id = $delete_id";
    $con->query($delete_sql);
    header("Location: manage_donors.php");
    exit;
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
    </style>
</head>
<body>
    <h2>All Registered Donors</h2>
    <table>
    <?php
$sql = "SELECT * FROM doners";
$result = $con->query($sql);
if ($result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        if ($edit_donor && $edit_donor['id'] == $row['id']):
?>
<tr>
    <form method="POST" action="manage_donors.php">
        <input type="hidden" name="id" value="<?= $edit_donor['id']; ?>">
        <td><?= $edit_donor['id']; ?></td>
        <td><input type="text" name="name" value="<?= htmlspecialchars($edit_donor['name']); ?>"></td>
        <td><input type="text" name="blood_group" value="<?= htmlspecialchars($edit_donor['blood_group']); ?>"></td>
        <td><input type="number" name="Age" value="<?= htmlspecialchars($edit_donor['Age']); ?>"></td>
        <td><input type="text" name="contact" value="<?= htmlspecialchars($edit_donor['contact']); ?>"></td>
        <td><input type="text" name="location" value="<?= htmlspecialchars($edit_donor['location']); ?>"></td>
        <td>
            <button type="submit" name="update">Update</button>
            <a href="manage_donors.php">Cancel</a>
        </td>
    </form>
</tr>
<?php
        else:
?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><?= htmlspecialchars($row['name']); ?></td>
    <td><?= htmlspecialchars($row['blood_group']); ?></td>
    <td><?= htmlspecialchars($row['Age']); ?></td>
    <td><?= htmlspecialchars($row['contact']); ?></td>
    <td><?= htmlspecialchars($row['location']); ?></td>
    <td class="action-buttons">
        <a href="manage_donors.php?edit_id=<?= $row['id']; ?>" class="edit-btn">Edit</a>
        <a href="manage_donors.php?delete_id=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this donor?');">Delete</a>
    </td>
</tr>
<?php
        endif;
    endwhile;
else:
?>
<tr><td colspan="7">No donors found.</td></tr>
<?php endif; ?>

    </table>
</body>
</html>

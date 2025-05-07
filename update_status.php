<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = isset($_POST['request_id']) ? $_POST['request_id'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if (!empty($request_id) && !empty($status)) {
        // Update the status in the database
        $query = "UPDATE blood_requests SET status = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('si', $status, $request_id);

        if ($stmt->execute()) {
            echo "Status updated successfully!";
        } else {
            echo "Failed to update status.";
        }
    }
}
?>

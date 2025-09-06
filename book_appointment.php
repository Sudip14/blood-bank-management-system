<?php
session_start();
include 'connection.php'; // $con must be defined here

// Check login
if (!isset($_SESSION['doners_id'])) {
    header("Location: login.php");
    exit();
}

$doners_id = $_SESSION['doners_id'];
$message = '';

// ✅ Fetch donor's last donation date
$lastDonationQuery = $con->prepare("SELECT last_donation_date FROM doners WHERE id=?");
$lastDonationQuery->bind_param("i", $doners_id);
$lastDonationQuery->execute();
$lastDonationResult = $lastDonationQuery->get_result();
$lastDonation = $lastDonationResult->fetch_assoc();
$lastDonationQuery->close();

$nextEligibleDate = '';
if (!empty($lastDonation['last_donation_date'])) {
    $nextEligibleDate = date('Y-m-d', strtotime($lastDonation['last_donation_date'] . ' +3 months'));
}

// ✅ Handle appointment booking
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['book'])) {
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];
    $location = trim($_POST['location']);

    // Validate inputs
    if (empty($date) || empty($time) || empty($location)) {
        $message = "❌ All fields are required.";
    } else {
        // 1. Prevent double booking
        $stmt1 = $con->prepare("SELECT appointment_date FROM appointments WHERE doners_id=? AND appointment_date >= CURDATE() AND status IN ('Pending','Approved') LIMIT 1");
        $stmt1->bind_param("i", $doners_id);
        $stmt1->execute();
        $res1 = $stmt1->get_result();

        if ($res1->num_rows > 0) {
            $existingAppointment = $res1->fetch_assoc();
            $message = "⚠️ You already have an appointment on {$existingAppointment['appointment_date']}.";
        }
        $stmt1->close();

        // 2. Enforce 3-month rule
        if (empty($message) && !empty($lastDonation['last_donation_date'])) {
            $minDate = date('Y-m-d', strtotime($lastDonation['last_donation_date'] . ' +3 months'));
            if ($date < $minDate) {
                $message = "⚠️ Last donation: {$lastDonation['last_donation_date']}. Next eligible from $minDate.";
            }
        }

        // 3. Insert appointment if valid
        if (empty($message)) {
            $stmt2 = $con->prepare("INSERT INTO appointments (doners_id, appointment_date, appointment_time, location, status) VALUES (?, ?, ?, ?, 'Pending')");
            $stmt2->bind_param("isss", $doners_id, $date, $time, $location);

            if ($stmt2->execute()) {
                $message = "✅ Appointment booked successfully at $location!";
            } else {
                $message = "❌ Error booking appointment: " . htmlspecialchars($con->error);
            }
            $stmt2->close();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6f8; margin: 0; color: #333; }
        .navbar { background-color: #2c3e50; overflow: hidden; padding: 10px 20px; }
        .navbar a { float: left; color: white; padding: 12px 16px; text-decoration: none; font-weight: 600; }
        .navbar a:hover { background-color: #1a252f; }
        .navbar a:last-child { float: right; }
        h2, h3 { text-align: center; color: #2c3e50; margin: 20px 0; }
        form { max-width: 500px; margin: 0 auto 30px; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        label { display: block; margin-bottom: 6px; font-weight: 500; }
        input, select { width: 100%; padding: 10px; margin-bottom: 16px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; }
        input[type="submit"] { background-color: #3498db; color: white; font-weight: 600; cursor: pointer; }
        input[type="submit"]:hover { background-color: #2980b9; }
        .message { text-align: center; font-size: 16px; font-weight: 500; margin: 10px 0; }
        .success { color: green; }
        .error { color: red; }
        table { width: 90%; margin: 0 auto 40px; border-collapse: collapse; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px 16px; border-bottom: 1px solid #e0e0e0; }
        th { background: #34495e; color: #fff; }
        tr:hover { background-color: #f9f9f9; }
    </style>
</head>
<body>

<!-- ✅ Navigation -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="book_appointment.php">Book Appointment</a>
    <a href="my_requests.php">My Requests</a>
    <a href="logout.php">Logout</a>
</div>

<h2>Book Blood Donation Appointment</h2>

<?php if ($message): ?>
    <p class="message <?php echo (strpos($message, '✅') !== false) ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </p>
<?php endif; ?>

<form method="POST">
    <label>Date:</label>
    <input type="date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">

    <label>Location:</label>
    <select name="location" required>
        <option value="">-- Select Location --</option>
        <option value="Bhaktapur Blood Center">Bhaktapur Blood Center</option>
        <option value="Kathmandu Central Hospital">Kathmandu Central Hospital</option>
        <option value="Lalitpur Red Cross">Lalitpur Red Cross</option>
    </select>

    <label>Time:</label>
    <input type="time" name="appointment_time" required>

    <input type="submit" name="book" value="Book Appointment">
</form>

<h3>Your Appointments</h3>
<table>
    <tr><th>Date</th><th>Time</th><th>Status</th><th>Location</th></tr>
    <?php
    $stmt = $con->prepare("SELECT appointment_date, appointment_time, status, location FROM appointments WHERE doners_id=? ORDER BY appointment_date DESC");
    $stmt->bind_param("i", $doners_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . htmlspecialchars($row['appointment_date']) . "</td>
                <td>" . htmlspecialchars($row['appointment_time']) . "</td>
                <td>" . htmlspecialchars($row['status']) . "</td>
                <td>" . htmlspecialchars($row['location']) . "</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No appointments found.</td></tr>";
    }
    $stmt->close();
    ?>
</table>

</body>
</html>

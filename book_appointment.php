<?php
session_start();
include 'connection.php'; // defines $con

// Check if donor is logged in
if (!isset($_SESSION['doners_id'])) {
    header("Location: login.php");
    exit();
}

$doners_id = $_SESSION['doners_id'];
$message = '';

// Handle appointment booking
if (isset($_POST['book'])) {
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];
    $location = $_POST['location'];

    // Check slot availability at the same location
    $check = mysqli_query($con, "
        SELECT * FROM appointments 
        WHERE appointment_date='$date' 
        AND appointment_time='$time' 
        AND location='$location'
    ");

    if (mysqli_num_rows($check) > 0) {
        $message = "This slot at $location is already booked. Please choose another.";
    } else {
        $insert = mysqli_query($con, "
            INSERT INTO appointments (doners_id, appointment_date, appointment_time, location)
            VALUES ('$doners_id','$date','$time','$location')
        ");
        $message = $insert ? "Appointment booked successfully at $location!" : "Error booking appointment: " . mysqli_error($con);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 40px;
            color: #333;
        }

        h2, h3 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        form {
            max-width: 500px;
            margin: 0 auto 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
        }

        input[type="date"],
        input[type="time"],
        select,
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        p {
            text-align: center;
            font-size: 16px;
            font-weight: 500;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #34495e;
            color: #fff;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        @media (max-width: 600px) {
            form, table {
                width: 100%;
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<h2>Book Blood Donation Appointment</h2>

<?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>

<form method="POST">
    <label>Date:</label>
    <input type="date" name="appointment_date" required>

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
$query = "SELECT appointment_date, appointment_time, status, location FROM appointments WHERE doners_id = ? ORDER BY appointment_date DESC";
$stmt = $con->prepare($query);

if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($con->error));
}

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
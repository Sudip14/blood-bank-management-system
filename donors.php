<?php
include 'connection.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
</section>

<!-- === Donor Section === -->
<section id="donors" class="donor-section">
<div class="donor-container">
<h2>Featured <span>Donors</span></h2>
<p>Here are a few of our active and verified blood donors.</p>
<div class="donor-grid">
    <?php
    // Connect to database
    $conn = new mysqli("localhost", "root", "", "blood_bank");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch 4 donors
    $sql = "SELECT name, blood_group, location FROM doners LIMIT 4";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output each donor
        while ($row = $result->fetch_assoc()) {
            echo '<div class="donor-card">';
            echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
            echo '<p><strong>Blood Group:</strong> ' . htmlspecialchars($row["blood_group"]) . '</p>';
            echo '<p><strong>Location:</strong> ' . htmlspecialchars($row["location"]) . '</p>';
            echo '</div>';
        }
    } else {
        echo "<p>No donors found.</p>";
    }

    $conn->close();
    ?>
</div>
<a href="register.php" class="cta-btn">Become a Donor</a>
</div>
</section>

</body>
</html>

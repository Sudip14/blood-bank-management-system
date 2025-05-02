<?php include 'connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donors</title>
    <!-- Font Awesome for icons (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .donor-section {
            background: #fff;
            padding: 4rem 2rem;
            text-align: center;
        }

        .donor-container h2 {
            font-size: 2.2rem;
            color: #d10000;
        }

        .donor-container span {
            font-weight: bold;
        }

        .donor-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0;
        }

        .donor-card {
            background: #fef6f6;
            border: 2px solid #ffdcdc;
            border-radius: 10px;
            padding: 1.5rem;
            width: 250px;
            box-shadow: 0 4px 10px rgba(209, 0, 0, 0.05);
        }

        .donor-card h3 {
            color: #d10000;
            margin-bottom: 0.5rem;
        }

        .donor-card p {
            color: #555;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<!-- === Donor Section === -->
<section id="donors" class="donor-section">
    <div class="donor-container">
        <h2><span>Donors</span></h2>

        <div class="donor-grid">
            <?php
            // Use the existing connection from connection.php
            $sql = "SELECT name, blood_group, age, contact, location FROM doners";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="donor-card">';
                    echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
                    echo '<p><strong>Blood Group:</strong> ' . htmlspecialchars($row["blood_group"]) . '</p>';
                    echo '<p><strong>Age:</strong> ' . htmlspecialchars($row["age"]) . '</p>';
                    echo '<p><strong>Contact:</strong> ' . htmlspecialchars($row["contact"]) . '</p>';
                    echo '<p><strong>Location:</strong> ' . htmlspecialchars($row["location"]) . '</p>';
                    echo '</div>';
                }
            } else {
                echo "<p>No donors found.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</section>

</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Find Blood</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background: #f8f8f8;
        }

        header {
            background: red;
            color: white;
            padding: 20px;
            font-size: 24px;
        }

        .container {
            padding: 20px;
        }

        .section {
            margin: 20px auto;
            padding: 20px;
            width: 80%;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
            background: white;
        }

        .btn {
            background: red;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn:hover {
            background: darkred;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .donor-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .donor {
            background: #ffeaea;
            border: 1px solid #ffcccc;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(255, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.2s;
        }

        .donor:hover {
            transform: scale(1.02);
            background: #fff0f0;
        }

        .donor img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 2px solid red;
        }

        .donor h3 {
            margin: 0 0 10px 0;
            color: red;
        }

        .donor p {
            margin: 5px 0;
            font-size: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <header>Find Blood Donors</header>

    <div class="container">
        <div class="section">
            <h2>Search for Blood Donors</h2>
            <form method="post">
                <input type="text" name="blood_group" placeholder="Enter Blood Group (e.g., A+)" required />
                <input type="text" name="location" placeholder="Enter Location (optional)" />
                <button type="submit" name="search" class="btn">Search</button>
            </form>
            <div id="donorList" class="donor-list">
                <?php
                if (isset($_POST['search'])) {
                    $blood_group = $_POST['blood_group'];
                    $location = trim($_POST['location']);

                    if (!empty($location)) {
                        // Prioritize exact location matches, then others with the same blood group
                        $stmt = $con->prepare("
                            SELECT name, blood_group, contact, location,
                                CASE WHEN location = ? THEN 1 ELSE 0 END AS priority
                            FROM doners
                            WHERE blood_group = ?
                            ORDER BY priority DESC, name ASC
                        ");
                        $stmt->bind_param("ss", $location, $blood_group);
                    } else {
                        // If no location provided, sort by name only
                        $stmt = $con->prepare("
                            SELECT name, blood_group, contact, location, status
                            FROM doners
                            WHERE blood_group = ?
                            ORDER BY name ASC
                        ");
                        $stmt->bind_param("s", $blood_group);
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='donor'>
                                    <h3>{$row['name']}</h3>
                                    <p>Blood Group: {$row['blood_group']}</p>
                                    <p>Contact: {$row['contact']}</p>
                                    <p>Location: {$row['location']}</p>
                                    <p>Status: {$row['status']}</p>
                                </div>";
                        }
                    } else {
                        echo "<p>No donors found.</p>";
                    }
                    $stmt->close();
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>

<?php
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .donor-results {
            text-align: left;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <header>Find Blood Donors</header>

    <div class="container">
        <div class="section">
            <h2>Search for Blood Donors</h2>
            <form method="post">
                <input type="text" name="blood_group" placeholder="Enter Blood Group (e.g., A+)" required>
                <button type="submit" name="search" class="btn">Search</button>
            </form>
            <div id="searchResults" class="search-results"></div>
            <div id="donorList" class="donor-list">
                <?php
                if (isset($_POST['search'])) {
                    $blood_group = $_POST['blood_group'];
                    $stmt = $con->prepare("SELECT name, blood_group, contact, location FROM doners WHERE blood_group = ?");
                    $stmt->bind_param("s", $blood_group);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='donor'>
                            <h3>{$row['name']}</h3>
                            <p>Blood Group: {$row['blood_group']}</p>
                            <p>Contact: {$row['contact']}</p>
                            <p>Location: {$row['location']}</p>
                        </div>";
                        }
                    } else {
                        echo "<p>No donors found.</p>";
                    }
                    $stmt->close();
                }
                ?>
            </div>
            <div id="donorResults" class="donor-results"></div>
        </div>
    </div>

</body>

</html>
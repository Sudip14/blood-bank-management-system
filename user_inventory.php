<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    // If the user is not logged in, redirect to login page
    header("Location: login.php");
    exit;
    
}
// Assuming user details are stored in session variables
$user_email = $_SESSION['user_email'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

include 'connection.php';

// Initialize variables
$search = "";
$message = "";

// Handle blood request form submission
if (isset($_POST['request_blood'])) {
    $requested_blood_group = $_POST['blood_group'];
    $requested_units = intval($_POST['units']);

    if ($requested_units <= 0) {
        $message = "❌ Units must be greater than zero.";
    } else {
        // Check inventory
        $stmt = $con->prepare("SELECT units_available FROM inventory WHERE blood_group = ?");
        $stmt->bind_param("s", $requested_blood_group);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['units_available'] >= $requested_units) {
                // Insert into blood_requests table
                $name = $_SESSION['user_name'] ?? '';
                $contact = $_SESSION['user_contact'] ?? '';
                $city = $_SESSION['user_location'] ?? '';

                $stmt = $con->prepare("INSERT INTO blood_requests (name, blood_group, units_requested, city, contact, request_time, user_email, user_id) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)");
                $stmt->bind_param("ssssssi", $name, $requested_blood_group, $requested_units, $city, $contact, $user_email, $user_id);
                $stmt->execute();

                // Update inventory
                $stmt = $con->prepare("UPDATE inventory SET units_available = units_available - ? WHERE blood_group = ?");
                $stmt->bind_param("is", $requested_units, $requested_blood_group);
                $stmt->execute();

                $message = "✅ Blood request submitted successfully!";
            } else {
                $message = "❌ Not enough units available.";
            }
        } else {
            $message = "❌ Blood group not found.";
        }
    }
}

// Handle search
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
    <title>Blood Bank Inventory</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            margin: 0;
            text-align: center;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #d10000;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
        }

        .logo span {
            color: #ffcc00;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: 500;
            font-size: 1rem;
            transition: opacity 0.3s ease;
        }

        nav ul li a:hover {
            opacity: 0.8;
        }

        .badge {
            background-color: white;
            color: #d10000;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 50%;
            position: absolute;
            top: -5px;
            right: -10px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 10px 0;
            list-style: none;
            margin: 0;
            top: 100%;
            right: 0;
            min-width: 160px;
            z-index: 1000;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content li a {
            display: block;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
        }

        .dropdown-content li a:hover {
            background-color: #f2f2f2;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
        }

        .mobile-menu-btn {
            display: none;
        }

        @media (max-width: 768px) {
            #navMenu {
                display: none;
                flex-direction: column;
                background-color: #d10000;
                position: absolute;
                top: 60px;
                right: 0;
                width: 200px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }

            #navMenu.show {
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: white;
            }

            nav ul li a {
                padding: 10px 20px;
            }
        }

        h1 { background: red; color: white; padding: 20px; margin: 0; }
        form { margin: 20px auto; width: 300px; }
        input, select, button {
            padding: 10px;
            width: 80%;
            margin: 10px 5%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: red;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: darkred;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px gray;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: red;
            color: white;
        }
        tr.low-stock {
            background: #ffe6e6;
        }
        .message {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<header>
    <div class="header-container">
        <div class="logo">Blood<span>Care</span></div>
        <nav>
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <ul id="navMenu">
                <li><a href="index.php">Home</a></li>
                <li><a href="#donors">Donors</a></li>
                <li><a href="user_inventory.php" class="active">Request Blood</a></li>
                <li><a href="search.php">Find Donors</a></li>
                <li><a href="#about-us">About Us</a></li>
                <li><a href="my_requests.php">My Requests</a></li>
                <li class="dropdown" style="position: relative;">
                    <a href="#" id="bellBtn">
                        <i class="fa-regular fa-bell"></i>
                        <span class="badge">1</span>
                    </a>
                    <div id="bellNotification" style="display: none;">
                        <?php ob_start(); include 'notification.php'; ob_end_flush(); ?>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#">
                        <div class="user-info">
                            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            <i class="fa-solid fa-user"></i>
                        </div>
                    </a>
                    <ul class="dropdown-content">
                        <li><a href="user_profile_update.php">Update Profile</a></li>
                        <li><a href="user_logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</header>


</head>
<body>

    <h1>Blood Bank Inventory</h1>

    <?php if (!empty($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <!-- Search Form -->
    <form method="post">
        <input type="text" name="search_text" placeholder="Search Blood Group..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" name="search">🔍 Search</button>
    </form>

    <!-- Blood Request Form -->
    <form method="post">
        <select name="blood_group" required>
            <option value="">Select Blood Group</option>
            <?php
            $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
            foreach ($bloodGroups as $group) {
                echo "<option value=\"$group\">$group</option>";
            }
            ?>
        </select>

        <input type="number" name="units" placeholder="Enter Units" min="1" required>
        <button type="submit" name="request_blood">🩸 Request Blood</button>
    </form>

    <!-- Inventory Table -->
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
                        <?php if ($row['units_available'] > 0): ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="blood_group" value="<?= $row['blood_group'] ?>">
                                <input type="hidden" name="units" value="1">
                                <button type="submit" name="request_blood">🩸 Request</button>
                            </form>
                        <?php else: ?>
                            <span style="color:red;">Out of Stock</span>
                        <?php endif; ?>
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

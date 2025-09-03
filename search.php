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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
/* --- Your CSS stays the same --- */
body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8f8f8; }
.header-container { display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background-color: #d10000; color: white; position: sticky; top: 0; z-index: 1000; }
.logo { font-size: 1.8rem; font-weight: bold; color: white; }
.logo span { color: #ffcc00; }
nav ul { display: flex; list-style: none; gap: 25px; margin: 0; padding: 0; align-items: center; }
nav ul li a { text-decoration: none; color: white; font-weight: 500; font-size: 1rem; transition: opacity 0.3s ease; }
nav ul li a:hover { opacity: 0.8; }
.badge { background-color: white; color: #d10000; font-size: 0.75rem; padding: 2px 6px; border-radius: 50%; position: absolute; top: -5px; right: -10px; }
.dropdown-content { display: none; position: absolute; background-color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 10px 0; list-style: none; margin: 0; top: 100%; right: 0; min-width: 160px; z-index: 1000; }
.dropdown:hover .dropdown-content { display: block; }
.dropdown-content li a { display: block; padding: 10px 20px; color: #333; text-decoration: none; }
.dropdown-content li a:hover { background-color: #f2f2f2; }
.user-info { display: flex; align-items: center; gap: 8px; color: white; }
.mobile-menu-btn { display: none; }
@media (max-width: 768px) {
    #navMenu { display: none; flex-direction: column; background-color: #d10000; position: absolute; top: 60px; right: 0; width: 200px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
    #navMenu.show { display: flex; }
    .mobile-menu-btn { display: block; background: none; border: none; font-size: 24px; cursor: pointer; color: white; }
    nav ul li a { padding: 10px 20px; }
}
.container { padding: 20px; text-align: center; }
.section { margin: 20px auto; padding: 20px; width: 80%; border-radius: 10px; box-shadow: 0 0 10px gray; background: white; }
.btn { background: red; color: white; padding: 10px 15px; border: none; cursor: pointer; margin-top: 10px; font-size: 16px; border-radius: 5px; }
.btn:hover { background: darkred; }
input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
.donor-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
.donor { background: #ffeaea; border: 1px solid #ffcccc; border-radius: 12px; padding: 20px; box-shadow: 0 4px 10px rgba(255, 0, 0, 0.1); display: flex; flex-direction: column; align-items: center; transition: transform 0.2s; }
.donor:hover { transform: scale(1.02); background: #fff0f0; }
.donor h3 { margin: 0 0 10px 0; color: red; }
.donor p { margin: 5px 0; font-size: 15px; text-align: center; }
</style>
</head>

<body>
<header>
<div class="header-container">
<div class="logo">Blood<span>Care</span></div>
<nav>
<button class="mobile-menu-btn" id="mobileMenuBtn"><i class="fas fa-bars"></i></button>
<ul id="navMenu">
<li><a href="index.php">Home</a></li>
<li><a href="#donors">Donors</a></li>
<li><a href="user_inventory.php">Request Blood</a></li>
<li><a href="search.php">Find Donors</a></li>
<li><a href="#about-us">About Us</a></li>
<li><a href="my_requests.php">View My Requests</a></li>
<li class="dropdown" style="position: relative;">
<a href="#" id="bellBtn"><i class="fa-regular fa-bell"></i><span class="badge">1</span></a>
<div id="bellNotification" style="display: none;">
<?php ob_start(); include 'notification.php'; ob_end_flush(); ?>
</div>
</li>
<li class="dropdown">
<?php if (!isset($_SESSION['user_id'])): ?>
<a href="#">Login <i class="fa-solid fa-user"></i></a>
<ul class="dropdown-content">
<li><a href="admin_login.php">Admin Login</a></li>
<li><a href="login.php">User Login</a></li>
</ul>
<?php else: ?>
<a href="#"><div class="user-info"><span><?= htmlspecialchars($_SESSION['user_name']) ?></span><i class="fa-solid fa-user"></i></div></a>
<ul class="dropdown-content">
<li><a href="user_profile_update.php">Update Profile</a></li>
<li><a href="user_logout.php">Logout</a></li>
</ul>
<?php endif; ?>
</li>
</ul>
</nav>
</div>
</header>

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
        $stmt = $con->prepare("
            SELECT name, blood_group, contact, location,
                CASE WHEN location = ? THEN 1 ELSE 0 END AS priority
            FROM doners
            WHERE blood_group = ?
            ORDER BY priority DESC, name ASC
        ");
        $stmt->bind_param("ss", $location, $blood_group);
    } else {
        $stmt = $con->prepare("
            SELECT name, blood_group, contact, location
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
                    <h3>" . htmlspecialchars($row['name']) . "</h3>
                    <p>Blood Group: " . htmlspecialchars($row['blood_group']) . "</p>
                    <p>Contact: " . htmlspecialchars($row['contact']) . "</p>
                    <p>Location: " . htmlspecialchars($row['location']) . "</p>
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

<script>
document.getElementById('bellBtn').addEventListener('click', function(e){
    e.preventDefault();
    const note = document.getElementById('bellNotification');
    note.style.display = note.style.display === 'none' ? 'block' : 'none';
});

document.getElementById('mobileMenuBtn').addEventListener('click', function(){
    const navMenu = document.getElementById('navMenu');
    navMenu.classList.toggle('show');
});
</script>
</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/connection.php'; // safer path

if (!isset($_SESSION['user_id'])) {
    echo "<div class='notification error'>Please log in to see notifications.</div>";
    return;
}

$userId = $_SESSION['user_id'];

$stmt = $con->prepare("SELECT name, last_donation_date FROM doners WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $lastDonation = $row['last_donation_date'];
    $name = htmlspecialchars($row['name']);

    if ($lastDonation) {
        $nextEligible = date('Y-m-d', strtotime($lastDonation . ' +3 months'));
        $today = date('Y-m-d');

        if ($today >= $nextEligible) {
            echo "<div class='notification success'>
                    ✅ Hello <strong>$name</strong>, you are now eligible to donate blood again!
                    <span class='close-btn' onclick=\"this.parentElement.style.display='none';\">❌</span>
                  </div>";
        } else {
            echo "<div class='notification error'>
                    ⛔ Not eligible yet.<br>Next eligible date: <strong>$nextEligible</strong>
                    <span class='close-btn' onclick=\"this.parentElement.style.display='none';\">❌</span>
                  </div>";
        }
    } else {
        echo "<div class='notification error'>No donation history found.</div>";
    }
} else {
    echo "<div class='notification error'>User data not found.</div>";
}
?>

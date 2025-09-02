<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include __DIR__ . '/connection.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    echo "<div class='notification error'>Please log in to see notifications.</div>";
    return;
}

$userId = $_SESSION['user_id'];

/**
 * Algorithm: Donor Eligibility Notification
 * Purpose: Notify donor if eligible for next blood donation
 * Input: $userId
 * Output: Notification message
 */
function donorEligibilityNotification($con, $userId) {
    // Step 1: Fetch donor record
    $stmt = $con->prepare("SELECT name, last_donation_date FROM doners WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Step 2: Check if donor exists
    if ($row = $result->fetch_assoc()) {
        $lastDonation = $row['last_donation_date'];
        $name = htmlspecialchars($row['name']);

        // Step 3: If donor has donation history
        if ($lastDonation) {
            // Step 4: Calculate next eligible date
            $nextEligible = date('Y-m-d', strtotime($lastDonation . ' +3 months'));
            $today = date('Y-m-d');

            // Step 5: Compare current date with next eligible date
            if ($today >= $nextEligible) {
                return "<div class='notification success'>
                        ✅ Hello <strong>$name</strong>, you are now eligible to donate blood again!
                        <span class='close-btn' onclick=\"this.parentElement.style.display='none';\">❌</span>
                      </div>";
            } else {
                return "<div class='notification error'>
                        ⛔ Not eligible yet.<br>Next eligible date: <strong>$nextEligible</strong>
                        <span class='close-btn' onclick=\"this.parentElement.style.display='none';\">❌</span>
                      </div>";
            }
        } else {
            // Step 6: No donation history
            return "<div class='notification error'>No donation history found.</div>";
        }
    } else {
        // Step 7: User not found
        return "<div class='notification error'>User data not found.</div>";
    }
}

// Execute the algorithm
echo donorEligibilityNotification($con, $userId);
?>

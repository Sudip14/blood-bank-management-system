<?php
session_start();
include 'connection.php'; // Include your database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Management System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dropdown-content {
            display: none;
            position: absolute;
            background: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 10px 0;
            list-style: none;
            margin: 0;
            top: 100%;
            right: 0;
            min-width: 150px;
            z-index: 1000;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content li a {
            display: block;
            padding: 8px 15px;
            color: black;
            text-decoration: none;
        }

        .dropdown-content li a:hover {
            background-color: #f2f2f2;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-info img {
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            #navMenu {
                display: none;
                flex-direction: column;
                background: white;
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
                color: black;
            }
        }
    </style>
</head>
<body>

<!-- ==== Header (Navigation Bar) ==== -->
<header>
    <div class="header-container">
        <div class="logo">Blood<span>Care</span></div>
        <nav>
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <ul id="navMenu">
                <li><a href="#home">Home</a></li>
                <li><a href="#donors">Donors</a></li>
                <li><a href="user_inventory.php">Request Blood</a></li>
                <li><a href="search.php">Find Donors</a></li>
                <li><a href="#about-us">About Us</a></li>
                <li><a href="my_requests.php">View My Requests</a>
                </li>
                <li class="dropdown">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="#">Login <i class="fa-duotone fa-solid fa-user"></i></a>
                        <ul class="dropdown-content">
                            <li><a href="admin_login.php">Admin Login</a></li>
                            <li><a href="login.php">User Login</a></li>
                        </ul>
                    <?php else: ?>
                        <a href="#">
                            <div class="user-info">
                                <!-- <img src="user_icon.png" alt="User" style="width: 24px; height: 24px;"> -->
                                <span><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                                <i class="fa-duotone fa-solid fa-user"></i>
                           </div>
                        </a>
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

<!-- JS for mobile toggle -->
<script>
    document.getElementById('mobileMenuBtn').addEventListener('click', function() {
        const navMenu = document.getElementById('navMenu');
        navMenu.classList.toggle('show');
    });
</script>

</header>

<!-- Optional JS to enhance dropdown toggle -->
<script>
    const dropdown = document.querySelector('.dropdown');
    const dropdownContent = dropdown.querySelector('.dropdown-content');

    dropdown.addEventListener('mouseenter', () => {
        dropdownContent.style.display = 'block';
    });

    dropdown.addEventListener('mouseleave', () => {
        dropdownContent.style.display = 'none';
    });
</script>

    </header>

    <!-- ==== Main Content ==== -->
    <main>
        <!-- Hero Section
        <div class="hero">
            <img src="Images/Bloodcare.webp" alt="Blood Donation" class="hero-image">
            <h1>Welcome to BloodCare</h1>
            <p>Your trusted blood bank management system.</p>
            <a href="search.php" class="cta-btn">Find a Donor</a>
        </div> -->


        <!-- === Home Section === -->
<section id="home" class="home-section">
    <div class="home-container">
        <h2>Welcome to <span>BloodCare</span></h2>
        <p>Our platform helps connect those in need of blood with lifesaving donors in real-time.</p>
        <div class="home-boxes">
            <div class="home-box">
                <i class="fas fa-tint"></i>
                <h3>Why Donate?</h3>
                <p>Donating blood can save up to 3 lives with each donation.</p>
            </div>
            <div class="home-box">
                <i class="fas fa-hospital"></i>
                <h3>Trusted Network</h3>
                <p>Hospitals and donors work together on a safe and verified platform.</p>
            </div>
        </div>
    </div>
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


        
      <!-- === About Us Section === -->
<section id="about-us" class="about-section">
    <div class="about-container">
        <!-- Left Column - Content -->
        <div class="about-content">
            <h2>About <span>BloodCare</span></h2>
            <p class="tagline">Saving Lives Through Technology</p>
            <p>Founded in 2025, BloodCare is Nepal's premier digital blood bank platform connecting donors with recipients in real-time. Our mission is to eliminate blood shortages through innovation.</p>
            
            <div class="features-box">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Rapid Matching</h3>
                    <p>Find compatible donors in under 15 minutes</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>100% Safe</h3>
                    <p>Rigorous donor screening and testing</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>24/7 Availability</h3>
                    <p>Emergency services anytime, anywhere</p>
                </div>
            </div>
        </div>
    
    </div>

    <style>

        /* === home Styles === */
        .home-section {
    background-color: #fff0f0;
    padding: 4rem 2rem;
    text-align: center;
}
.home-container h2 {
    font-size: 2.2rem;
    color: #d10000;
}
.home-container span {
    font-weight: bold;
}
.home-boxes {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 2rem;
    margin-top: 2rem;
}
.home-box {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(209, 0, 0, 0.05);
    width: 280px;
    transition: 0.3s;
}
.home-box:hover {
    transform: translateY(-5px);
}
.home-box i {
    font-size: 2rem;
    color: #d10000;
    margin-bottom: 0.5rem;
}
.home-box h3 {
    margin-bottom: 0.5rem;
    color: #333;
}
.home-box p {
    color: #666;
}

    /* === donner Section Styles === */
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

   /* === user logout css === */
   .user-info {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.user-info:hover .dropdown-content {
    display: block;
}




        /* === About Us Section Styles === */
        .about-section {
            padding: 5rem 2rem;
            background: linear-gradient(to right, #fff 0%, #fef6f6 100%);
        }
        
        .about-container {
            display: flex;
            align-items: center;
            gap: 4rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .about-content {
            flex: 1;
        }
        
        .about-content h2 {
            font-size: 2.5rem;
            color: #d10000;
            margin-bottom: 0.5rem;
        }
        
        .about-content h2 span {
            font-weight: 700;
        }
        
        .tagline {
            color: #555;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .about-content p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 2rem;
        }
        
        .features-box {
            display: flex;
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .feature {
            flex: 1;
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(209, 0, 0, 0.05);
            transition: transform 0.3s ease;
            border-top: 3px solid #d10000;
        }
        
        .feature:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(209, 0, 0, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .feature-icon i {
            color: #d10000;
            font-size: 1.2rem;
        }
        
        .feature h3 {
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .feature p {
            color: #777;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .about-image {
            flex: 1;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(209, 0, 0, 0.1);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s ease;
        }
        
        .about-image:hover img {
            transform: scale(1.03);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .about-container {
                flex-direction: column;
            }
            
            .features-box {
                flex-direction: column;
            }
            
            .about-image {
                order: -1;
                margin-bottom: 2rem;
            }
        }
    </style>
</section>
  <!-- === Our Team Section === -->
<section class="team-section">
    <div class="team-container">
        <h2 class="section-title">Our <span>Team</span></h2>
        <p class="section-subtitle">Meet the passionate minds behind our mission</p>
        
        <div class="team-grid">
            <!-- Team Member 1 -->
            <div class="team-member">
                <div class="member-image">
                    <img src="Images/rabin.jpg" alt="Member 1">
                </div>
                <div class="member-info">
                    <h3>Rabin Chuwan</h3>
                    <p>Student</p>
                    <div class="social-links">
                        <a href="#"><i class="https://www.linkedin.com/in/rabin-chuwan-7b7559260/"></i></a>
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="team-member">
                <div class="member-image">
                    <img src="Images/vasi.jpg" alt="Member 2">
                </div>
            <div class="member-info">
                <h3>Sudip Bhasima</h3>
                <p>Student</p>
                <div class="social-links">
                    <a href="#"><i class="https://www.linkedin.com/in/sudip-bhasima-47183b305/"></i></a>
                    <a href="#"><i class="fas fa-envelope"></i></a>
                </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* === Our Team Section Styles === */
        .team-section {
            padding: 4rem 1rem;
            background: #fef6f6; /* Light pink background */
            text-align: center;
        }
        
        .section-title {
            font-size: 2.2rem;
            color: #d10000;
            margin-bottom: 0.5rem;
        }
        
        .section-title span {
            font-weight: 700;
        }
        
        .section-subtitle {
            color: #777;
            margin-bottom: 2.5rem;
            font-size: 1.1rem;
        }
        
        .team-grid {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .team-member {
            width: 300px;
            text-align: center;
            padding: 1.5rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(209, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        
        .team-member:hover {
            transform: translateY(-10px);
        }
        
        .member-image {
            width: 180px;
            height: 180px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid #ffecec;
            box-shadow: 0 3px 10px rgba(209, 0, 0, 0.1);
        }
        
        .member-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .team-member:hover .member-image img {
            transform: scale(1.1);
        }
        
        .member-info h3 {
            color: #333;
            margin-bottom: 0.3rem;
            font-size: 1.3rem;
        }
        
        .member-info p {
            color: #d10000;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        .social-links a {
            color: #777;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            color: #d10000;
            transform: scale(1.2);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .team-grid {
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }
            
            .team-member {
                width: 100%;
                max-width: 350px;
            }
        }
    </style>
</section>
    <!-- ==== Footer ==== -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Bode Bhaktapur, Nepal</li>
                    <li><i class="fas fa-phone"></i> 98989898989</li>
                    <li><i class="fas fa-envelope"></i> blood@gmail.com</li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Donate Blood</a></li>
                    <li><a href="#">Find Blood</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>

        <div class="copyright">
            <p>&copy; 2025 BloodCare. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Smooth scrolling for "About Us" link
        document.querySelector('a[href="#about-us"]').addEventListener('click', function(event) {
            event.preventDefault();
            document.querySelector('#about-us').scrollIntoView({ behavior: 'smooth' });
        });
    </script>
</body>
</html>
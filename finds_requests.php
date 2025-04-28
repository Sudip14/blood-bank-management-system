<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Requests | BloodCare Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: #fef6f6;
            min-height: 100vh;
            display: flex;
        }
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #d10000;
            color: #fff;
            position: fixed;
            height: 100%;
            padding-top: 2rem;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.8rem;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar ul li {
            padding: 15px 20px;
            transition: 0.3s;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1rem;
            display: block;
        }
        .sidebar ul li:hover, .sidebar ul li.active {
            background: #a50000;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            flex: 1;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .header h1 {
            font-size: 2rem;
            color: #d10000;
        }
        .search-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(209, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        .search-section form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .search-section input, .search-section select, .search-section button {
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            flex: 1;
            min-width: 200px;
        }
        .search-section button {
            background: #d10000;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        .search-section button:hover {
            background: #a50000;
        }
        /* Results Table */
        .results {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(209, 0, 0, 0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        table th, table td {
            padding: 0.8rem;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        table th {
            background: #d10000;
            color: white;
        }

        /* Footer */
        footer {
            margin-left: 250px;
            background: #d10000;
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .main-content, footer {
                margin-left: 200px;
            }
        }
        @media (max-width: 576px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }
            .main-content, footer {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>BloodCare Admin</h2>
        <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-user"></i> Manage Donors</a></li>
            <li><a href="blood_inventory.php"><i class="fas fa-tint"></i> Blood Inventory</a></li>
            <li class="active"><a href="find_requests.php"><i class="fas fa-search"></i> Find Requests</a></li>
            <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Find Blood Requests</h1>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <form action="" method="post">
                <select name="blood_group">
                    <option value="">Select Blood Group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>

                <input type="text" name="city" placeholder="Enter City">

                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Results Section -->
        <div class="results">
            <h2>Search Results</h2>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Blood Group</th>
                        <th>City</th>
                        <th>Contact</th>
                        <th>Request Date</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample Data (Replace with PHP while fetching from database) -->
                    <tr>
                        <td>e</td>
                        <td>O+</td>
                        <td>Kathmandu</td>
                        <td>+977-9876543210</td>
                        <td>2025-04-28</td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>A-</td>
                        <td>Pokhara</td>
                        <td>+977-9801234567</td>
                        <td>2025-04-27</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 BloodCare Admin Panel. All rights reserved.</p>
    </footer>

</body>
</html>

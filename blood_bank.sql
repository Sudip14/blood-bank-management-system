-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2025 at 12:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blood_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `doners_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Pending','Confirmed','Completed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `doners_id`, `appointment_date`, `appointment_time`, `status`, `created_at`, `location`) VALUES
(1, 1, '2025-08-28', '12:12:00', 'Completed', '2025-08-26 07:02:17', NULL),
(2, 1, '2025-08-27', '14:03:00', 'Confirmed', '2025-08-26 07:09:40', NULL),
(3, 1, '2025-05-01', '13:03:00', 'Pending', '2025-08-26 07:24:41', NULL),
(4, 1, '2025-05-01', '13:03:00', 'Pending', '2025-08-26 07:28:12', 'Bhaktapur Blood Center');

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `id` int(11) NOT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `units_requested` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `request_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending',
  `user_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`id`, `blood_group`, `units_requested`, `user_id`, `name`, `city`, `contact`, `request_time`, `status`, `user_email`) VALUES
(26, 'A+', 6, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-04 05:52:24', 'Approved', 'bhasimasudeep05@gmail.com'),
(27, 'A+', NULL, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-04 05:54:52', '', 'bhasimasudeep05@gmail.com'),
(28, 'A+', NULL, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-04 05:59:07', '', 'bhasimasudeep05@gmail.com'),
(29, 'A+', NULL, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-04 06:05:53', '', 'bhasimasudeep05@gmail.com'),
(30, 'A+', 1, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-04 06:26:08', 'Approved', 'bhasimasudeep05@gmail.com'),
(31, 'AB+', 1, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-04 06:26:37', '', 'bhasimasudeep05@gmail.com'),
(32, 'AB+', 3, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-04 06:27:01', 'Approved', 'bhasimasudeep05@gmail.com'),
(33, 'A+', 1, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-10 16:17:52', 'Rejected', 'bhasimasudeep05@gmail.com'),
(34, 'AB+', 1, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-11 12:03:02', 'Approved', 'bhasimasudeep05@gmail.com'),
(35, 'A+', 1, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-11 12:12:11', '', 'bhasimasudeep05@gmail.com'),
(36, 'AB+', 1, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-11 12:15:10', '', 'bhasimasudeep05@gmail.com'),
(37, 'AB+', 1, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-06-11 12:23:35', 'Pending', 'bhasimasudeep05@gmail.com'),
(38, 'O+', 1, 1, 'sudeep bhasima', 'bhaktapur', '9866443370', '2025-08-26 09:52:16', 'Pending', 'bhasimasudeep05@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `doners`
--

CREATE TABLE `doners` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` tinyint(4) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `contact` varchar(25) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `location` varchar(100) NOT NULL,
  `last_donation_date` date DEFAULT NULL,
  `times_donated` int(11) DEFAULT 0,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doners`
--

INSERT INTO `doners` (`id`, `name`, `age`, `gender`, `contact`, `email`, `password`, `blood_group`, `location`, `last_donation_date`, `times_donated`, `status`) VALUES
(1, 'sudeep bhasima', 20, 'Male', '9866443370', 'bhasimasudeep05@gmail.com', '$2y$10$ZG3I7oI8XcpzmUBY4/aQFOtPsfkE9gUvtTt723rkkyKA9.zo9i8kq', 'b+', 'bhaktapur', '2025-01-01', 1, NULL),
(2, 'sudip bhasima', 21, 'Male', '9866443376', 'prazr23@gmail.com', '$2y$10$D51b.SWZ680hTjRbB2.Qpu8gSm3ZRnHaWl17MkdCGwn2WJgl4Bsga', 'A+', 'bkt', NULL, 0, NULL),
(3, 'ram', 26, 'Male', '9866443375', 'ram@gmail.com', '$2y$10$q8sdaGM0wIK88KzMXiHUFeboPPRhBL90CeCHBSJ3LtBkoRx0tXWpK', 'ab-', 'ktm', '2025-06-11', 1, NULL),
(4, 'sduip', 23, 'Male', '9866440070', 'bhasimasudip05@gmail.com', '$2y$10$AdkaenSRBk.9tAcMnO2B2uBrx/twT/qs3x.LhEu4VMh02OYbwaP4m', 'B+', 'bkt', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `units_available` int(11) DEFAULT 0,
  `name` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `blood_group`, `units_available`, `name`, `city`, `contact`) VALUES
(1, 'A+', 0, NULL, NULL, NULL),
(2, 'A-', 0, NULL, NULL, NULL),
(3, 'B+', 0, NULL, NULL, NULL),
(4, 'B-', 0, NULL, NULL, NULL),
(5, 'O+', 13, NULL, NULL, NULL),
(6, 'O-', 0, NULL, NULL, NULL),
(7, 'AB+', 7, NULL, NULL, NULL),
(8, 'AB-', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_log`
--

CREATE TABLE `inventory_log` (
  `id` int(11) NOT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `change_type` enum('add','remove') DEFAULT NULL,
  `units_changed` int(11) DEFAULT NULL,
  `change_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_log`
--

INSERT INTO `inventory_log` (`id`, `blood_group`, `change_type`, `units_changed`, `change_date`) VALUES
(1, 'A+', 'add', 10, '2025-04-26 16:56:37'),
(2, 'B-', 'add', 12, '2025-04-27 15:09:12'),
(3, 'A+', 'remove', 9, '2025-04-29 15:41:51'),
(4, 'A+', 'add', 20, '2025-05-02 13:11:46'),
(5, 'AB+', 'add', 16, '2025-05-02 17:14:09'),
(6, 'A+', 'add', 8, '2025-05-05 01:32:47'),
(7, 'O+', 'add', 14, '2025-06-11 12:32:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `role` enum('admin','donor') NOT NULL DEFAULT 'donor',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `contact`, `role`, `created_at`) VALUES
(1, 'sudip14', 'bhasimasudeep05@gmail.com', '$2y$10$Chd8p4jqM.aNECMvs81rau1sLhsD1LIruR8P/0UaHdF99tLJ2/0Se', 'sudeep bhasima', '9866443370', 'admin', '2025-05-02 05:50:56'),
(2, 'praz1', 'prazr23@gmail.com', '$2y$10$RStnyCuTAB5Npnk5GK4c3ufilvtCjNSIKV/7UZUCzwfD42lCPV6TW', 'sudeep bhasima', '9866443376', 'admin', '2025-05-02 10:02:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_doner` (`doners_id`);

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doners`
--
ALTER TABLE `doners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blood_group` (`blood_group`);

--
-- Indexes for table `inventory_log`
--
ALTER TABLE `inventory_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `doners`
--
ALTER TABLE `doners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inventory_log`
--
ALTER TABLE `inventory_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_doner` FOREIGN KEY (`doners_id`) REFERENCES `doners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

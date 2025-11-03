-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2025 at 06:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ems`
--

-- --------------------------------------------------------

--
-- Table structure for table `assign_project`
--

CREATE TABLE `assign_project` (
  `id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assign_project`
--

INSERT INTO `assign_project` (`id`, `project_name`, `employee_id`, `start_date`, `end_date`, `description`) VALUES
(1, 'Mobile App Development', 7, '2025-08-25', '2025-09-26', 'ios app build!!'),
(2, 'Inventory Management System', 10, '2025-08-26', '2025-08-31', 'That is build a IOT App For Smart Homes'),
(3, 'E-commerce Platform', 10, '2025-08-26', '2025-09-02', 'Clothing Brand Ecommerce'),
(11, 'Database Design', 9, '2025-08-28', '2025-09-12', 'Not'),
(13, 'Financial Tracking System', 11, '2025-09-09', '2025-09-29', 'Best Financial Report Website Build');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Leave') DEFAULT 'Present',
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `date`, `status`, `check_in_time`, `check_out_time`, `created_at`) VALUES
(3, 7, '2025-08-22', 'Present', NULL, NULL, '2025-08-22 09:55:51'),
(7, 7, '2025-08-23', 'Present', NULL, NULL, '2025-08-23 11:27:12'),
(8, 9, '2025-08-29', 'Present', NULL, NULL, '2025-08-29 11:57:48'),
(9, 9, '2025-08-30', 'Present', NULL, NULL, '2025-08-30 09:07:35');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) NOT NULL,
  `position` varchar(50) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `full_name`, `email`, `phone`, `department`, `salary`, `position`, `join_date`, `created_at`) VALUES
(7, 21, 'Sarkhedi Mahek', 'sarkhedimahek@gmail.com', '98745422658', 'HR', 2000.00, 'manager', '2025-08-22', '2025-08-22 09:14:48'),
(9, 23, 'Sarkhedi Om', 'om@gmail.com', '7283973272', 'HR', 36000.00, 'Manager', '2025-08-23', '2025-08-23 10:08:18'),
(10, 25, 'Jenis Sanandiya', 'jenis@gmail.com', '7869595682', 'IT', 29000.00, 'IOT APP', '2025-08-26', '2025-08-26 10:24:20'),
(11, 26, 'Mahek Patel', 'mahek@gmail.com', '7283973271', 'Finance', 36540.00, 'Head Of Finance Manager', '2025-09-09', '2025-09-09 15:59:01');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `applied_date` date DEFAULT curdate(),
  `approved_by` int(11) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `applied_date`, `approved_by`, `approved_date`, `created_at`) VALUES
(4, 7, 'Sick', '2025-08-22', '2025-08-23', 'etc', 'Approved', '2025-08-22', NULL, '2025-08-22 10:03:12', '2025-08-22 10:03:06'),
(6, 9, 'Cacual leave', '2025-08-23', '2025-08-24', NULL, 'Rejected', '2025-08-23', NULL, '2025-08-25 08:35:46', '2025-08-23 10:09:34'),
(13, 9, 'Annual', '2025-08-29', '2026-01-08', NULL, 'Pending', '2025-08-29', NULL, NULL, '2025-08-29 11:57:37'),
(14, 9, 'Casual', '2025-08-29', '2025-08-30', NULL, 'Rejected', '2025-08-29', NULL, '2025-08-29 12:32:27', '2025-08-29 12:15:54'),
(15, 9, 'Emergency', '2025-08-29', '2025-09-01', NULL, 'Rejected', '2025-08-29', NULL, '2025-08-29 12:32:25', '2025-08-29 12:28:34'),
(16, 9, 'Maternity', '2025-08-29', '2025-08-29', NULL, 'Pending', '2025-08-29', NULL, NULL, '2025-08-29 12:31:30'),
(17, 9, 'Sick', '2025-09-07', '2025-09-07', NULL, 'Pending', '2025-09-07', NULL, NULL, '2025-09-07 10:07:12'),
(18, 9, 'Sick', '2025-09-07', '2025-09-07', NULL, 'Pending', '2025-09-07', NULL, NULL, '2025-09-07 10:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `password_updates`
--

CREATE TABLE `password_updates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `old_password_hash` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `allowances` decimal(10,2) DEFAULT 0.00,
  `deductions` decimal(10,2) DEFAULT 0.00,
  `net_salary` decimal(10,2) GENERATED ALWAYS AS (`basic_salary` + `allowances` - `deductions`) STORED,
  `pay_date` date NOT NULL,
  `pay_month` varchar(7) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `employee_id`, `basic_salary`, `allowances`, `deductions`, `pay_date`, `pay_month`, `created_at`) VALUES
(2, 7, 15000.00, 3600.00, 1200.00, '2025-08-22', '', '2025-08-22 10:13:59'),
(4, 9, 36000.00, 1000.00, 0.00, '2025-09-05', '', '2025-09-05 03:58:08');

-- --------------------------------------------------------

--
-- Table structure for table `project_status`
--

CREATE TABLE `project_status` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `status` enum('Assigned','In Progress','Completed','On Hold','Cancelled') NOT NULL,
  `status_comment` text DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `status_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_status`
--

INSERT INTO `project_status` (`id`, `project_id`, `status`, `status_comment`, `updated_by`, `status_date`) VALUES
(2, 11, 'In Progress', 'Need A Time To Let\'s Complete', 9, '2025-08-28 15:28:02'),
(8, 11, 'On Hold', NULL, 9, '2025-08-28 15:41:08'),
(9, 11, 'In Progress', NULL, 9, '2025-08-28 15:41:50'),
(10, 11, 'On Hold', NULL, 9, '2025-08-28 15:54:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_approved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `is_approved`) VALUES
(8, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-08-20 10:39:24', 1),
(23, 'om', '$2y$10$yNbip4SzrPWRzfQKTcrRneCnHwD35TVMhk9DnEZyki9NLyJ9qM./y', 'employee', '2025-08-23 10:08:18', 1),
(25, 'jenis', '$2y$10$YIiGlNvT0FKWUj3QKtxvFeYVVz6q1mynXBASYhzftj7UevmSHjE/a', 'employee', '2025-08-26 10:24:20', 1),
(26, 'Mahek', '$2y$10$tFv8WRKf7ZGQ9oB3h1d08OYwLmlipJHwsUvSXXZPERg1cB46hx.NK', 'employee', '2025-09-09 15:59:01', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assign_project`
--
ALTER TABLE `assign_project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_employee` (`employee_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `status` (`status`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `password_updates`
--
ALTER TABLE `password_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `pay_month` (`pay_month`);

--
-- Indexes for table `project_status`
--
ALTER TABLE `project_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assign_project`
--
ALTER TABLE `assign_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `password_updates`
--
ALTER TABLE `password_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `project_status`
--
ALTER TABLE `project_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assign_project`
--
ALTER TABLE `assign_project`
  ADD CONSTRAINT `fk_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `password_updates`
--
ALTER TABLE `password_updates`
  ADD CONSTRAINT `password_updates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_status`
--
ALTER TABLE `project_status`
  ADD CONSTRAINT `project_status_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

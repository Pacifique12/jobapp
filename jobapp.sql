-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2024 at 11:58 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$Bki5dNCpSpjm/ow1gcP0XeSFukYnVcW5paysTeo34NyrlnG1U8Gvi');

-- --------------------------------------------------------

--
-- Table structure for table `applicant_details`
--

CREATE TABLE `applicant_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `application_status` varchar(255) NOT NULL DEFAULT 'Pending',
  `application_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `applicant_details`
--

INSERT INTO `applicant_details` (`id`, `user_id`, `job_id`, `payment_id`, `application_status`, `application_date`) VALUES
(11, 8, 2, NULL, 'Pending', '2024-04-03 08:32:02'),
(12, 8, 1, NULL, 'Pending', '2024-04-03 08:39:28'),
(16, 7, 3, NULL, 'Pending', '2024-04-03 10:32:12'),
(20, 8, 1, NULL, 'Pending', '2024-04-16 19:02:56'),
(21, 26, 1, NULL, 'approved', '2024-04-16 20:56:04'),
(22, 26, 1, NULL, 'approved', '2024-04-16 20:56:13'),
(23, 27, 1, NULL, 'Pending', '2024-04-17 08:47:10');

-- --------------------------------------------------------

--
-- Table structure for table `balance`
--

CREATE TABLE `balance` (
  `b_id` int(11) NOT NULL,
  `st_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `balance`
--

INSERT INTO `balance` (`b_id`, `st_id`, `amount`, `status`) VALUES
(2, 28, 10000, 'Active'),
(3, 29, 10000, 'Active'),
(4, 30, 10000, 'Active'),
(5, 31, 10000, 'Active'),
(6, 32, 10000, 'Active'),
(7, 33, 10000, 'Active'),
(8, 34, 10000, 'Active'),
(9, 35, 10000, 'Active'),
(10, 36, 10000, 'Active'),
(11, 37, 10000, 'Active'),
(12, 38, 10000, 'Active'),
(13, 39, 10000, 'Active'),
(14, 40, 10000, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `job_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `job_title`, `company_name`, `job_description`) VALUES
(1, 'IT Support', 'Com LTD', 'This Job require Candidate with A0 in IT'),
(2, 'Data Analyst', 'Com LTD', 'This Job Require candidate with at Least A1 in Data Analysis'),
(3, 'Project Manager', 'City Trading LTD', 'This job Requires Candidate with A0 in management');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `user_id`, `amount`, `payment_date`) VALUES
(23, 26, '10000.00', '2024-04-16 21:02:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `job` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `certificate` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `Registration Date` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` varchar(255) NOT NULL DEFAULT 'Waiting payment',
  `session_id` varchar(255) NOT NULL DEFAULT uuid(),
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `name`, `phone`, `email`, `job`, `sex`, `certificate`, `username`, `password`, `Registration Date`, `Status`, `session_id`, `feedback`) VALUES
(7, 'NIYOGISUBIZO Pacifique', '0780349070', 'npacifique437@gmail.com', 'Project Manager', 'Male', 'Diploma 2, Advanced Diploma 1, Bachelors Degree', 'paccy', '$2y$10$5IUGSEBl4u1.idxj.sl08.OFu8P2cORIu0KrRfrGxPnIWi.bMjkvm', '2024-03-04 02:05:00', 'Rejected', '1ee4a4d2-e436-11ee-b8cd-80fa5b2ee5d0', NULL),
(8, 'UWINDATWA Marie Victoire', '0789416471', 'uwindatwamarievictoire1@gmail.com', 'IT Support', 'Female', 'Advanced Diploma 1', 'Victoire', '$2y$10$D5HnFvQC5kbx.nIytJXccexoLEiM3ZtT4bcJgugqjyjpd5Kb3OwJq', '2024-03-04 14:24:35', 'approved', '1ee4a854-e436-11ee-b8cd-80fa5b2ee5d0', NULL),
(10, 'Emily Brown', '555-2222', 'emily@example.com', 'Project Manager', 'Female', 'Master Degree', 'emily', '$2y$10$qa38LsZS.fYtWk6I2Z9K2.on3TT2Widgt7sOWCzz18IAEuEKENZxG', '2024-03-16 15:13:27', 'approved', '1ee4abc7-e436-11ee-b8cd-80fa5b2ee5d0', NULL),
(26, 'Daniel', '0780349078', 'da@gmail.com', 'IT Support', 'Male', 'A0', 'daniel325', '$2y$10$lkZ5lYTFNGt8Hc77CJEsNex.1ECrhKF6JZzPXaQqkGXqeTwsvdJnS', '2024-04-16 20:51:47', 'Rejected', '23e7b23a-fc33-11ee-b030-745d226e702c', 'dfgjl'),
(27, 'vick', '0780349075', 'vicky@gmail.com', 'IT Support', 'Male', 'A1', 'vick604', '$2y$10$0sfjK7WJIngmVwPU5cbTfOMwbBoHfMztX3ThJFH7PVqpkM4kLuzAC', '2024-04-16 21:21:18', 'Waiting payment', '43cf7f04-fc37-11ee-b030-745d226e702c', NULL),
(28, 'victire', '0780349071', 'vickyr@gmail.com', 'Unknown', 'Male', 'A1', 'vick932', '$2y$10$Zu03iHnnMmh4K720hhPPAuYMZErZtEydKnIk7oBHnwwVgc43BECTS', '2024-04-16 21:22:31', 'approved', '6f1c5321-fc37-11ee-b030-745d226e702c', NULL),
(29, 'paccy', '0780349074', 'npavy@g', 'Unknown', 'Female', 'PHD', '', '$2y$10$Vb7Gl5SZ5WvDRyQpyX0ebeyC1i9m5z9y5FnDJsDvJj1bDHF8xibgW', '2024-05-07 12:43:05', 'Waiting payment', '59727885-0c6f-11ef-94d7-80fa5b2ee5d0', NULL),
(30, 'paccy', '0780349074', 'npavy@g', 'Unknown', 'Female', 'PHD', '', '$2y$10$KPPjWILhzTjK8XDXcBKUCeUTMIjEqfL.rV3eoKjG6CiR.OhLqof2S', '2024-05-07 12:56:12', 'Waiting payment', '2e7aa4de-0c71-11ef-94d7-80fa5b2ee5d0', NULL),
(31, 'paccy', '0780349074', 'paccy', 'Unknown', 'Male', 'PHD', '', '$2y$10$Sf4lPzX5rj1YOTzZg05ajO2vIx2qpRgd25VC16u6pv9.mykDihWFO', '2024-05-07 12:59:08', 'Waiting payment', '97c93182-0c71-11ef-94d7-80fa5b2ee5d0', NULL),
(32, 'paccy', '0780349074', 'paccy', 'Unknown', 'Male', 'PHD', '', '$2y$10$4xtPbucVND1gf2ONCNQBxez6Z0edgqcVIjJDa.Zh8ROO89QL0rDX6', '2024-05-07 13:01:07', 'Waiting payment', 'de4bbd13-0c71-11ef-94d7-80fa5b2ee5d0', NULL),
(33, 'hy', '07803490743', '1', 'Unknown', 'Male', 'PHD', '', '$2y$10$7cfEHtG2qAcxmD/vYJRLUuTO5IDNt5H7XPOTl6kZOLsb/izdSLxle', '2024-05-07 13:36:35', 'Waiting payment', 'd2d58992-0c76-11ef-94d7-80fa5b2ee5d0', NULL),
(34, 'patrick', '078034907432', 'pat@22', 'Unknown', 'Female', 'PHD', '', '$2y$10$N1ND8L4loQgllVTqU.L7ne1cyt4fOpHHak0BeX6MXMpEsuW9mhIiy', '2024-05-07 14:23:22', 'Waiting payment', '5c08a3d7-0c7d-11ef-94d7-80fa5b2ee5d0', NULL),
(35, 'patrick', '+250780449074', 'pat@22', 'Unknown', 'Female', 'PHD', '', '$2y$10$MBt8tOelw9a7QtG1IOr5Q.Fvk0xM.7ka1wRR.6tw3MJ8KjD0JAaWG', '2024-05-07 14:28:41', 'Waiting payment', '1a4d0178-0c7e-11ef-94d7-80fa5b2ee5d0', NULL),
(36, 'aime\n', '+250780449074', 'pat@22', 'Unknown', 'Female', 'PHD', '', '$2y$10$jxj2V5JoPbwkoSVfcTH40e8HmK5xd.rsZf7FoRNYNZqKgx4q2Hg0e', '2024-05-07 14:34:48', 'Waiting payment', 'f4ad4369-0c7e-11ef-94d7-80fa5b2ee5d0', NULL),
(37, 'peter', '078034907432', 'pet@123', 'Unknown', 'Female', 'PHD', '', '$2y$10$E7hgD81aMIVV64KAZNTJMOaiBCEWs1ErJlrcEmjsH6EmP7T5I9T0q', '2024-05-07 20:22:16', 'Waiting payment', '8a7ebbe0-0caf-11ef-94d7-80fa5b2ee5d0', NULL),
(38, 'petroo', '078034907432', 'email', 'Unknown', 'Female', 'PHD', '', '$2y$10$W5za0mvBtq2TCbIwm7NOuO.6Q227YHXFgjL89s2r8yyxl4UmilLsa', '2024-05-07 20:41:38', 'Waiting payment', '48dccfa4-0cb2-11ef-94d7-80fa5b2ee5d0', NULL),
(39, 'John', '078034907432', 'john@example.com', 'Unknown', 'Female', 'PHD', '', '$2y$10$lVwOYjZoieUVmez4zh2QsenXDVs4Ix98syC9h.m2TL/JnSvgknBnC', '2024-05-07 20:56:31', 'Waiting payment', '5d10b4c6-0cb4-11ef-94d7-80fa5b2ee5d0', NULL),
(40, 'pet', '078034907432', 'pett@', 'Unknown', 'Female', 'PHD', '', '$2y$10$WWIwd12ludud7F41YAFJ5.Bc0nhjGGHQlcd.g2THv3b2RiMz.F0se', '2024-05-07 21:13:55', 'Waiting payment', 'cb844b28-0cb6-11ef-94d7-80fa5b2ee5d0', NULL),
(41, 'anne', '078034907436', 'anne@123', '', 'M', 'PHD', '', '12345', '2024-05-07 21:52:14', 'Waiting payment', '25b55914-0cbc-11ef-94d7-80fa5b2ee5d0', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `applicant_details`
--
ALTER TABLE `applicant_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`b_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `applicant_details`
--
ALTER TABLE `applicant_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `balance`
--
ALTER TABLE `balance`
  MODIFY `b_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applicant_details`
--
ALTER TABLE `applicant_details`
  ADD CONSTRAINT `applicant_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `applicant_details_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `applicant_details_ibfk_3` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

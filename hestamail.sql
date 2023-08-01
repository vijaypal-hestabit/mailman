-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 01, 2023 at 07:19 PM
-- Server version: 8.0.33-0ubuntu0.20.04.2
-- PHP Version: 8.1.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hestamail`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int NOT NULL,
  `path` varchar(255) NOT NULL,
  `inbox_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blind_carbon_copy`
--

CREATE TABLE `blind_carbon_copy` (
  `bcc_id` int NOT NULL,
  `bcc_email` varchar(255) NOT NULL,
  `inbox_id` int NOT NULL,
  `bcc_receiver_id` int NOT NULL,
  `is_trash` int NOT NULL DEFAULT '0',
  `is_read` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carbon_copy`
--

CREATE TABLE `carbon_copy` (
  `cc_id` int NOT NULL,
  `cc_email` varchar(255) NOT NULL,
  `inbox_id` int NOT NULL,
  `cc_receiver_id` int NOT NULL,
  `is_trash` int NOT NULL DEFAULT '0',
  `is_read` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

CREATE TABLE `inbox` (
  `id` int NOT NULL,
  `receiver_id` int DEFAULT NULL,
  `short_subject_msg` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'no subject',
  `sender_id` int NOT NULL,
  `receiver_delete` tinyint NOT NULL DEFAULT '0',
  `sender_delete` tinyint NOT NULL DEFAULT '0',
  `is_replied` tinyint NOT NULL DEFAULT '0',
  `draft_status` tinyint NOT NULL DEFAULT '0',
  `delivered_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `full_message` longtext NOT NULL,
  `is_read` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inbox`
--

INSERT INTO `inbox` (`id`, `receiver_id`, `short_subject_msg`, `sender_id`, `receiver_delete`, `sender_delete`, `is_replied`, `draft_status`, `delivered_date`, `full_message`, `is_read`) VALUES
(1, 1, 'test mail', 2, 1, 0, 0, 0, '2023-08-01 13:46:08', 'The lorem ipsum gets its name from the Latin phrase Neque porro quisquam est qui dolorem ipsum quia dolor sit amet. which translates to “Nor is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain.”', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user_name` varchar(50) NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `backup_mail` varchar(50) NOT NULL,
  `date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `reset_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `user_name`, `email`, `profile_pic`, `password`, `backup_mail`, `date`, `reset_link`) VALUES
(1, 'Vijay', 'Pal', 'vijay', 'vijay@mailman.com', '', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', 'zibov@mailinator.com', '2023-08-01 13:30:49', NULL),
(2, 'ashish', 'kumar', 'ashish', 'ashis@mailman.com', '1690897494_20220824_191410.jpg', '6abc9eba853ea08dd0e97810f68194e7', 'recovery@mailinator.com', '2023-08-01 13:44:54', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blind_carbon_copy`
--
ALTER TABLE `blind_carbon_copy`
  ADD PRIMARY KEY (`bcc_id`),
  ADD KEY `inbox_id` (`inbox_id`);

--
-- Indexes for table `carbon_copy`
--
ALTER TABLE `carbon_copy`
  ADD PRIMARY KEY (`cc_id`),
  ADD KEY `inbox_id` (`inbox_id`);

--
-- Indexes for table `inbox`
--
ALTER TABLE `inbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blind_carbon_copy`
--
ALTER TABLE `blind_carbon_copy`
  MODIFY `bcc_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carbon_copy`
--
ALTER TABLE `carbon_copy`
  MODIFY `cc_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inbox`
--
ALTER TABLE `inbox`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blind_carbon_copy`
--
ALTER TABLE `blind_carbon_copy`
  ADD CONSTRAINT `blind_carbon_copy_ibfk_1` FOREIGN KEY (`inbox_id`) REFERENCES `inbox` (`id`);

--
-- Constraints for table `carbon_copy`
--
ALTER TABLE `carbon_copy`
  ADD CONSTRAINT `carbon_copy_ibfk_1` FOREIGN KEY (`inbox_id`) REFERENCES `inbox` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

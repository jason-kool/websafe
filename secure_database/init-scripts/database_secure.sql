-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database_secure:3306
-- Generation Time: Aug 12, 2023 at 03:59 PM
-- Server version: 8.0.34
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `websafe`
--
CREATE DATABASE IF NOT EXISTS `websafe` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `websafe`;

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `id` int NOT NULL,
  `audit_username` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `audit_role` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `audit_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `audit_activity` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`id`, `audit_username`, `audit_role`, `audit_datetime`, `audit_activity`) VALUES
(30, 'greg', 'Admin', '2017-07-23 10:30:03', 'Logged in'),
(31, 'greg', 'Admin', '2017-07-23 10:31:57', 'Logged in'),
(32, 'greg', 'Admin', '2017-07-23 10:33:19', 'Logged in'),
(33, 'greg', 'Admin', '2017-07-23 10:34:12', 'Logged in'),
(34, 'greg', 'Admin', '2017-07-23 10:34:47', 'Logged in'),
(35, 'benny', 'user', '2017-07-23 10:39:07', 'Logged in'),
(36, 'greg', 'Admin', '2018-07-23 02:37:43', 'Logged in'),
(37, 'greg', 'Admin', '2018-07-23 03:33:34', 'Logged in'),
(38, 'greg', 'Admin', '2018-07-23 03:34:58', 'Logged in'),
(39, 'greg', 'Admin', '2018-07-23 03:35:31', 'Logged in'),
(40, 'benny', 'user', '2018-07-23 03:37:02', 'Logged in'),
(41, 'greg', 'Admin', '2018-07-23 03:37:27', 'Logged in'),
(42, 'benny', 'user', '2018-07-23 03:39:14', 'Logged in'),
(43, 'greg', 'Admin', '2018-07-23 03:40:55', 'Logged in'),
(44, 'benny', 'user', '2018-07-23 03:41:47', 'Logged in'),
(45, 'benny', 'user', '2018-07-23 04:26:49', 'Logged in'),
(46, 'greg', 'Admin', '2018-07-23 04:50:38', 'Logged in'),
(47, 'greg', 'Admin', '2023-07-18 04:57:00', 'Logged in'),
(48, 'benny', 'user', '2023-07-20 01:33:15', 'Logged in'),
(49, 'NIL', 'NIL', '2008-08-23 11:56:07', 'Failed to Login with username: helpme'),
(50, 'yes', 'user', '2023-08-08 11:57:32', 'Account logged in'),
(51, 'NIL', 'NIL', '2009-08-23 05:24:08', 'Failed to Login with username: helpme'),
(52, 'NIL', 'NIL', '2009-08-23 05:24:24', 'Failed to Login with username: yes'),
(53, 'yes', 'user', '2023-08-09 05:28:13', 'Account logged in'),
(54, 'yes', 'user', '2009-08-23 09:30:43', 'Account logged out'),
(55, 'helpme', 'user', '2023-08-09 05:33:16', 'Account logged in'),
(56, 'NIL', 'NIL', '2010-08-23 12:14:47', 'Failed to Login with username: admin'),
(57, 'NIL', 'NIL', '2010-08-23 12:14:56', 'Failed to Login with username: yes'),
(58, 'yes', 'user', '2023-08-10 12:15:13', 'Account logged in'),
(59, 'newadmin', 'user', '2023-08-10 01:04:49', 'Account logged in'),
(60, 'newadmin', 'admin', '2023-08-10 05:36:44', 'Account logged in'),
(61, 'newadmin', 'admin', '2010-08-23 09:36:46', 'Account logged out'),
(62, 'helpme', 'user', '2023-08-10 05:37:40', 'Account logged in'),
(63, 'helpme', 'user', '2023-08-10 05:38:28', 'Account logged in'),
(64, 'newadmin', 'admin', '2023-08-10 10:19:03', 'Account logged in'),
(65, 'newadmin', 'admin', '2023-08-10 10:43:40', 'Account logged in'),
(66, 'newadmin', 'admin', '2023-08-10 10:51:16', 'Account logged in'),
(67, 'benjamin', 'user', '2023-08-11 02:51:02', 'Account logged in'),
(68, 'benjamin', 'user', '2011-08-23 06:55:32', 'Account logged out'),
(69, 'helpme', 'user', '2023-08-12 12:28:14', 'Account logged in'),
(70, 'helpme', 'user', '2023-08-12 12:30:55', 'Account logged in'),
(71, 'helpme', 'user', '2023-08-12 03:39:35', 'Account logged in');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int NOT NULL,
  `user_id` int NOT NULL,
  `comment` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `posted_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `comment`, `posted_on`) VALUES
(71, 1, 'Very nice', '2023-07-28 17:10:53'),
(72, 4, 'I want to die', '2023-07-30 14:11:05'),
(74, 3, 'Any one who comments here will be banned', '2023-08-05 08:05:17'),
(75, 3, 'sex\r\n', '2023-08-05 08:05:36'),
(76, 1, 'test', '2023-08-05 08:06:35'),
(77, 17, 'My password P@ssw0rd', '2023-08-09 09:28:21'),
(78, 4, 'my password is HelpMe123\r\n', '2023-08-09 09:33:26');

-- --------------------------------------------------------

--
-- Table structure for table `otp_requests`
--

CREATE TABLE `otp_requests` (
  `id` int NOT NULL,
  `email` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `otp` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_requests`
--

INSERT INTO `otp_requests` (`id`, `email`, `otp`, `created_at`) VALUES
(1, 'yes@yes.com', '389090', '2023-08-09 09:24:33'),
(2, 'helpme@helpme.com', '698718', '2023-08-09 09:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `name` varchar(35) COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `picture` varchar(135) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `price`, `picture`, `description`) VALUES
(2, 'chanmalichan', 0.50, 'product_chanmalichan.jpg', 'chanmalichan oi oi'),
(3, 'smellypoopoo', 27.27, 'default.png', 'Ben\'s smellypoopoo'),
(7, 'AbGm', 200.00, 'product_AbGm.jpg', 'Ab C Eb\r\nG Bb D'),
(15, 'tettey terettey', 400.00, 'product_tettey_terettey.jpg', 'tettey terettey'),
(17, 'test', 123.00, 'product_test.png', 'Thanks guys');

-- --------------------------------------------------------

--
-- Table structure for table `scoreboard`
--

CREATE TABLE `scoreboard` (
  `scoreboard_id` int NOT NULL,
  `name` varchar(35) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(10) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(35) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `profilepicture` varchar(135) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `privilege` varchar(11) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `profilepicture`, `privilege`) VALUES
(1, 'benny', 'BENNY', 'Benny@gmail.com', 'benny_profile_pic.jpeg', 'user'),
(2, 'greg', 'greg', 'greg@gmail.com', 'greg.jpg', 'Admin'),
(3, 'admin', 'admin', 'admin@admin.com', '', 'admin'),
(4, 'helpme', '$2y$11$KQKG0p7YBfqxOS98VhVcSeKyhzp6aOaUNFQOeeI8euAi9Q.RX7/Y2', 'helpme@helpme.com', 'helpme_profile_pic.jfif', 'user'),
(17, 'yes', '$2y$11$FStTktdrMRUjZeIycJhbUO92jvkcTCdos8YkLXYe8ouy.7gecUWMi', 'yes@yes.com', '', 'user'),
(18, 'newadmin', '$2y$10$xPbXKyuNBWL7fG8yTyqjC.7dmjG6fMx230fEJgIY5rH9rbUwwe3Hu', 'newadmin@admin.com', '', 'admin'),
(19, 'benjamin', '$2y$11$vGtKJb4fHVJ0pxxOv4OREu8DXOSy5aGJlVm8izdDTAHKRTAh47m4S', 'benny@tp.com', 'benjamin_profile_pic.png', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_cart`
--

CREATE TABLE `user_cart` (
  `cart_id` int NOT NULL,
  `cartproduct_id` int NOT NULL,
  `cart_userid` int NOT NULL,
  `product_quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_cart`
--

INSERT INTO `user_cart` (`cart_id`, `cartproduct_id`, `cart_userid`, `product_quantity`) VALUES
(21, 2, 2, 30),
(56, 3, 1, 4),
(57, 2, 1, 3),
(68, 2, 4, 1),
(72, 3, 17, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_id_2` (`user_id`);

--
-- Indexes for table `otp_requests`
--
ALTER TABLE `otp_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `scoreboard`
--
ALTER TABLE `scoreboard`
  ADD PRIMARY KEY (`scoreboard_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `cartproduct_id` (`cartproduct_id`),
  ADD KEY `cart_userid` (`cart_userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `otp_requests`
--
ALTER TABLE `otp_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `scoreboard`
--
ALTER TABLE `scoreboard`
  MODIFY `scoreboard_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_cart`
--
ALTER TABLE `user_cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD CONSTRAINT `user_cart_ibfk_2` FOREIGN KEY (`cart_userid`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_cart_ibfk_3` FOREIGN KEY (`cartproduct_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

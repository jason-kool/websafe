-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: secure_database:3306
-- Generation Time: Aug 20, 2023 at 04:32 PM
-- Server version: 8.1.0
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
  `audit_username` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `audit_role` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `audit_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `audit_activity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`id`, `audit_username`, `audit_role`, `audit_datetime`, `audit_activity`) VALUES
(1, 'TestUser1', 'user', '2020-08-23 04:23:37', 'Account logged out'),
(2, 'TestUser1', 'user', '2023-08-21 12:23:48', 'Account logged in'),
(3, 'TestUser1', 'user', '2020-08-23 04:24:06', 'Account logged out'),
(4, 'TestUser1', 'user', '2023-08-21 12:26:21', 'Account logged in'),
(5, 'TestUser1', 'user', '2020-08-23 04:28:43', 'Account logged out'),
(6, 'Admin101', 'admin', '2023-08-21 12:29:58', 'Account logged in'),
(7, 'Admin101', 'admin', '2020-08-23 04:31:43', 'Account logged out'),
(8, 'Admin101', 'admin', '2023-08-21 12:32:00', 'Account logged in'),
(9, 'Admin101', 'admin', '2020-08-23 04:32:30', 'Account logged out');

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
(1, 3, 'Products have been awesome. Would definitely recommend to all!', '2023-08-20 15:18:36'),
(2, 1, 'Orders delivered as described. Would buy again', '2023-08-20 15:20:15'),
(3, 4, 'Love the products!! Value for $', '2023-08-20 15:20:56');

-- --------------------------------------------------------

--
-- Table structure for table `otp_requests`
--

CREATE TABLE `otp_requests` (
  `id` int NOT NULL,
  `email` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `otp` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `name` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `picture` varchar(135) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `price`, `picture`, `description`) VALUES
(1, 'Coffee Beans', 10.00, 'coffee-product-photo.jpg', 'Coffee Beans. 500grams. '),
(2, 'iPhone case', 9.90, 'cool-painted-iphone-case-blue-green.jpg', 'Cool iPhone casings, available for iPhone 7/8/10.'),
(3, 'Wireless Headphones', 24.90, 'wireless-headphones.jpg\r\n', 'Bluetooth wireless headphones. Compatible on all devices!'),
(4, 'Red LED Shoes', 34.90, 'red-LED-shoes.jpg', 'Wanna style yourself like Boots from Dora? You\'ve come to the right place because you\'ll definitely need one of these.'),
(5, 'Mustache Wax', 3.50, 'mustache-wax.jpg', 'What you\'ll need to get rid of that annoying mustache of yours! Get yours now!!'),
(6, 'Bath Bombs (3pcs)', 4.90, 'three-colorful-bath-bombs.jpg', 'Colourful bath bombs. Comes in packs of 3. Perfect gift for your loved ones!'),
(7, 'Yoga accessory bundle', 17.30, 'yoga-accessories.jpg', 'What you see is what you get. Special yoga bundle sale! ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `profilepicture` varchar(135) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `privilege` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `profilepicture`, `privilege`) VALUES
(1, 'TestUser1', '$2y$11$g2vG9FoQ.f9QQ8XEYgUlSeZ23beZaO9goHhuFm.xrcaKzRGlKAIO.', 'testuser1@test.com', '', 'user'),
(2, 'TestUser2', '$2y$11$IbClcCafFeLkaZLYEXtqv.74/xVPn9Eh9rjsR8xJfiTkzY2b8J8be', 'testuser2@test.com', '', 'user'),
(3, 'TestUser3', '$2y$11$sfYJlYHnGjLz/uhXgm9NVOPFLMj1kdKBRJ6StliSY4i4mbx7cvtze', 'testuser3@test.com', '', 'user'),
(4, 'TestUser4', '$2y$11$ZbUVrcEGbtfulTRnf6fMee98iDWLunBHBFNro4eT9oGXiI0eT.cJa', 'testuser4@test.com', '', 'user'),
(5, 'Admin101', '$2y$11$VZUBSArdMW2BC.GS6dAn1uyySnq3YJ2WU4WDxsSBVMeIndDxHo0m6', 'admin@websafe.com', '', 'admin');

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
(76, 1, 1, 2),
(77, 3, 1, 3),
(78, 2, 1, 9),
(79, 7, 2, 3),
(80, 5, 2, 1),
(81, 4, 3, 2),
(82, 1, 3, 3);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `otp_requests`
--
ALTER TABLE `otp_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_cart`
--
ALTER TABLE `user_cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

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

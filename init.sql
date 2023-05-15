-- phpMyAdmin SQL Dump
-- version 5.0.2
-- Server version: 10.4.13-MariaDB


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS c2;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `c2`
--

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `id` int(11) NOT NULL,
  `user_identifier` varchar(64) NOT NULL,
  `date` datetime NOT NULL,
  `severity` varchar(32) NOT NULL,
  `log` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `command`
--

CREATE TABLE `command` (
  `id` int(9) NOT NULL,
  `user_identifier` varchar(48) NOT NULL,
  `send_command` varchar(254) NOT NULL,
  `date` datetime NOT NULL,
  `challenge` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `recive`
--

CREATE TABLE `recive` (
  `id` int(9) NOT NULL,
  `user_identifier` varchar(48) NOT NULL,
  `revice_output` varchar(8196) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`) VALUES
(1, 'Admin', '$2y$10$oXEdOuZN3Dv6/66cT9IVr.PBRDTb2fH3iRjtOr/71YD.8MfduGl6C');

-- --------------------------------------------------------

--
-- Table structure for table `victims`
--

CREATE TABLE `victims` (
  `id` int(6) UNSIGNED NOT NULL,
  `HostName` varchar(30) DEFAULT NULL,
  `IP` varchar(30) DEFAULT NULL,
  `Active` varchar(30) DEFAULT NULL,
  `Last_Report` varchar(30) DEFAULT NULL,
  `OS` varchar(30) DEFAULT NULL,
  `UserName` varchar(30) DEFAULT NULL,
  `user_identifier` varchar(33) NOT NULL,
  `timezone` varchar(30) DEFAULT NULL,
  `uac` varchar(128) DEFAULT NULL,
  `admin_active` varchar(100) DEFAULT NULL,
  `isadmin` varchar(10) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `domain` varchar(50) DEFAULT NULL,
  `dir_location` varchar(50) DEFAULT NULL,
  `comments` varchar(254) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `command`
--
ALTER TABLE `command`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recive`
--
ALTER TABLE `recive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `victims`
--
ALTER TABLE `victims`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_identifier` (`user_identifier`),
  ADD KEY `user_identifier_2` (`user_identifier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audits`
--
ALTER TABLE `audits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1877;

--
-- AUTO_INCREMENT for table `command`
--
ALTER TABLE `command`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recive`
--
ALTER TABLE `recive`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `victims`
--
ALTER TABLE `victims`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

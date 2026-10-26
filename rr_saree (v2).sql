-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2026 at 11:26 PM
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
-- Database: `rr_saree`
--

CREATE DATABASE IF NOT EXISTS `rr_saree` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `rr_saree`;

-- --------------------------------------------------------

--
-- Table structure for table `itemlocate`
--

CREATE TABLE `itemlocate` (
  `itemid` int(11) NOT NULL,
  `locationid` int(11) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `itemlocate`
--

INSERT INTO `itemlocate` (`itemid`, `locationid`, `stock`) VALUES
(1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `itemname` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `imgurl` varchar(255) DEFAULT NULL,
  `category` enum('women','men','accessories') NOT NULL DEFAULT 'women'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `itemname`, `price`, `imgurl`, `category`) VALUES
(1, 'pic1', 500.00, 'assets/pic1.jpg', 'women');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `maplink` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location`, `maplink`) VALUES
(1, 'Palma', 'https://maps.google.com/?q=-20.274221,57.468132'),
(2, 'Rose Hill', 'https://maps.google.com/?q=-20.242224,57.471416'),
(3, 'Beau Bassin', 'https://maps.google.com/?q=-20.215302,57.469524'),
(4, 'Berthaud', 'https://maps.google.com/?q=-20.268894,57.470066');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `itemlocate`
--
ALTER TABLE `itemlocate`
  ADD PRIMARY KEY (`itemid`,`locationid`),
  ADD KEY `locationid` (`locationid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `itemlocate`
--
ALTER TABLE `itemlocate`
  ADD CONSTRAINT `foreignkey` FOREIGN KEY (`itemid`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foreignkey2` FOREIGN KEY (`locationid`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

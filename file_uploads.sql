-- phpMyAdmin SQL Dump
-- version 4.6.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 22, 2025 at 06:51 AM
-- Server version: 5.7.13-log
-- PHP Version: 5.6.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `file_uploads`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `description` text,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `filename`, `filepath`, `description`, `uploaded_at`) VALUES
(12, '1742578992_d6.jpg', 'uploads/1742578992_d6.jpg', 'รักแฟนจัง', '2025-03-21 17:43:12'),
(13, '1742578997_d1.jpg', 'uploads/1742578997_d1.jpg', '', '2025-03-21 17:43:17'),
(14, '1742579006_LINE_ALBUM_212023_240928_1.jpg', 'uploads/1742579006_LINE_ALBUM_212023_240928_1.jpg', '', '2025-03-21 17:43:26'),
(15, '1742579011_g4.jpg', 'uploads/1742579011_g4.jpg', '', '2025-03-21 17:43:31'),
(16, '1742579017_g2.jpg', 'uploads/1742579017_g2.jpg', '', '2025-03-21 17:43:37'),
(17, '1742579023_g1.jpg', 'uploads/1742579023_g1.jpg', '', '2025-03-21 17:43:43'),
(18, '1742579031_gggg.jpg', 'uploads/1742579031_gggg.jpg', '', '2025-03-21 17:43:51'),
(19, '1742579034_g.jpg', 'uploads/1742579034_g.jpg', '', '2025-03-21 17:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'dee', '$2y$10$.qc4UNw/nWnMc7MR0sQT9.BEq/6kv7lZQ.OOyCdCoVb8NySFnst26'),
(2, 'mint', '$2y$10$u6fwVtAtK99xuN3UNrPhyeJAWsaVbDFyAjvqeZzugw0lsZP4WaYJu'),
(3, 'dee1', '$2y$10$oGDPEOXBNxKunK4pW5svvOP4hzYcr8WKVzcyP8D5eVED.59qg1nTq'),
(4, 'mint1', '$2y$10$US7LHPxTeOsqsuBHsJbjvuz2g1VnsquzFwY/1dMJxz61k.0f1yIDi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

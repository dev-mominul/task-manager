-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 12, 2025 at 02:56 PM
-- Server version: 10.5.29-MariaDB
-- PHP Version: 8.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mominul_task_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(6) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('pending','in-progress','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(6) UNSIGNED DEFAULT NULL,
  `progress` int(3) DEFAULT 0,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `due_date`, `status`, `created_at`, `user_id`, `progress`, `description`) VALUES
(8, 'CSE333 Quiz 4', '2025-12-30', 'in-progress', '2025-04-14 14:44:36', 15, 0, 'Description text goes here'),
(9, 'CSE333 Quiz 3', '2025-12-29', 'pending', '2025-04-14 14:51:05', 15, 0, 'Description text goes here.'),
(10, 'CSE333 Project Show', '2025-12-01', 'completed', '2025-04-15 00:07:45', 15, 0, 'Develop model based project withing collab'),
(12, 'Final Exam 2', '2025-12-28', 'in-progress', '2025-04-15 05:59:40', 16, 0, 'Description goes here'),
(16, 'CSE415 Project Show', '2025-12-10', 'completed', '2025-12-10 06:34:08', 15, 0, 'Test description'),
(19, 'Test Task 9', '2025-12-30', 'pending', '2025-12-10 07:16:21', 15, 0, 'Description goes here...');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `name`) VALUES
(3, 'admin', 'admin@email.com', '$2y$10$iD5lxo3tIzk2mA5AHHXgUu.hYqB2sHo/ue3znxSrQhEEcIg7g9o6W', 'admin', '2025-04-14 05:36:42', 'System Admin'),
(15, 'user1', 'user1@email.com', '$2y$10$eQOPHf8IVknsHvRdqIXOTeyCv6ffGhiRjQXhBGvhIR0a3nONvlQ1y', 'user', '2025-08-21 19:39:33', 'Test User 1'),
(16, 'user2', 'user2@email.com', '$2y$10$Fb8j5VlEIgZraE9P/SZ7g.F6dFQavSSQJyPqNHnfkIj3ltCS7y4nS', 'user', '2025-08-22 00:19:32', 'Test User 2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

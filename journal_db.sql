-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2026 at 03:50 AM
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
-- Database: `journal_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `journal`
--

CREATE TABLE `journal` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `entry_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal`
--

INSERT INTO `journal` (`id`, `title`, `content`, `entry_date`, `created_at`, `updated_at`) VALUES
(2, 'First Try', 'I am writing this journal to try if my code are functioning', '2026-02-13', '2026-02-13 10:08:57', '2026-02-13 10:08:57'),
(3, 'Second', 'Woke up feeling optimistic about the new year. Made coffee, watched the sunrise from my window, and wrote down three goals I want to achieve. Small steps, big dreams.', '2026-02-13', '2026-02-13 10:09:38', '2026-02-13 10:09:38'),
(4, 'try', 'Called my teacher \"Mom\" in front of the whole class today. I wanted to disappear into the floor. She laughed it off but I could not stop thinking about it the entire day. But i consider her as a mom so no shame at all', '2026-02-13', '2026-02-13 10:09:56', '2026-02-13 10:11:48'),
(5, 'Can\'t sleep', 'It is 2am and I cannot sleep. Started questioning every life decision I have ever made. Why is it that the brain only wants to think about embarrassing memories from 5 years ago at midnight?', '2026-02-13', '2026-02-13 10:10:07', '2026-02-13 10:10:07'),
(6, 'Proud of me', 'Finished three pending tasks, cleaned my room, and actually ate a proper meal. This is the most functional I have felt in weeks. Rewarded myself with an entire episode of my favorite series.', '2026-02-13', '2026-02-13 10:10:20', '2026-02-13 10:10:20'),
(8, 'Goodjob', 'I am very proud that I have done this journal website and hopefully this is not just a project but I should use this in daily basis', '2026-02-13', '2026-02-13 14:20:10', '2026-02-13 14:20:10'),
(9, 'Error', 'Test again', '2026-02-13', '2026-02-13 14:22:02', '2026-02-14 01:58:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `journal`
--
ALTER TABLE `journal`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `journal`
--
ALTER TABLE `journal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

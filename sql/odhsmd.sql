-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2024 at 03:55 PM
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
-- Database: `odhsmd`
--

-- --------------------------------------------------------

--
-- Table structure for table `dbevents`
--

CREATE TABLE `dbevents` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `open_date` date NOT NULL,
  `description` text NOT NULL,
  `completed` text NOT NULL,
  `due_date` date DEFAULT NULL,
  `type` text DEFAULT NULL,
  `partners` text DEFAULT NULL,
  `amount` text DEFAULT NULL,
  `archived` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbevents`
--

INSERT INTO `dbevents` (`id`, `name`, `open_date`, `description`, `completed`, `due_date`, `type`, `partners`, `amount`, `archived`) VALUES
(9, 'test', '2025-01-01', 'test', 'incomplete', '2025-01-02', NULL, NULL, NULL, ''),
(11, 'Test 2', '2024-10-22', 'c', 'incomplete', '2024-10-25', NULL, NULL, NULL, ''),
(12, 'Test 3', '2024-10-26', 'testing', 'incomplete', '2024-10-31', NULL, NULL, NULL, ''),
(13, 'Test 4', '2024-10-29', 'Test 4', 'incomplete', '2024-12-31', NULL, NULL, NULL, ''),
(14, 'Test 5', '2025-01-01', 'Test 5', 'incomplete', '2025-01-31', NULL, NULL, NULL, ''),
(15, 'Test 6', '2024-11-05', 'Test 6', 'incomplete', '2024-11-30', NULL, NULL, NULL, ''),
(16, 'Test 7', '2024-10-22', 'Test 7', 'incomplete', '2024-10-25', NULL, NULL, NULL, ''),
(17, 'Test 8', '2024-10-24', 'Test 8', 'complete', '2024-10-31', NULL, NULL, NULL, ''),
(18, 'Grant test', '2024-11-21', 'A test grant for the demo', 'incomplete', '2024-11-22', 'test', '', '100', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `dbfields`
--

CREATE TABLE `dbfields` (
  `id` int(10) NOT NULL,
  `name` text NOT NULL,
  `data` text NOT NULL,
  `grant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbgrantfields`
--

CREATE TABLE `dbgrantfields` (
  `grant_id` int(11) NOT NULL,
  `field_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbgrantlinks`
--

CREATE TABLE `dbgrantlinks` (
  `grant_id` int(11) NOT NULL,
  `link_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dblinks`
--

CREATE TABLE `dblinks` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` varchar(500) NOT NULL,
  `grant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dblinks`
--

INSERT INTO `dblinks` (`id`, `name`, `link`, `grant_id`) VALUES
(1, 'Test link', 'www.umw.edu', 18);

-- --------------------------------------------------------

--
-- Table structure for table `dbmessages`
--

CREATE TABLE `dbmessages` (
  `id` int(11) NOT NULL,
  `grant_id` int(11),
  `person_id` varchar(256) NOT NULL,
  `senderID` varchar(256) NOT NULL,
  `recipientID` varchar(256) NOT NULL,
  `message_type` enum('open','due','report','custom') NOT NULL,
  `interval_type` enum('1Day','1Week','1Month','6Months','late','custom') NOT NULL,
  `scheduled_date` date NOT NULL,
  `sent` enum('notSent','sent','dismissed','done') NOT NULL DEFAULT 'notSent',
  `title` varchar(256) NOT NULL,
  `body` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `wasRead` tinyint(1) NOT NULL DEFAULT 0,
  `prioritylevel` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbmessages`
--

INSERT INTO `dbmessages` (`id`, `grant_id`, `person_id`, `senderID`, `recipientID`, `message_type`, `interval_type`, `scheduled_date`, `sent`, `title`, `body`, `time`, `wasRead`, `prioritylevel`) VALUES
(2673, 13, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 4 open date is coming up in one day', 'Test 4 is opening on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2674, 13, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 4 open date is coming up in one day', 'Test 4 is opening on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2675, 13, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 4 open date is coming up in one day', 'Test 4 is opening on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2676, 13, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 4 open date is coming up in one day', 'Test 4 is opening on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2677, 13, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 4 open date is coming up in one day', 'Test 4 is opening on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2678, 13, 'vmsroot', 'vmsroot', 'polack@um.edu', 'open', '1Day', '0000-00-00', 'sent', 'Test 4 open date is coming up in one day', 'Test 4 is opening on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2679, 13, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 4 open date is coming up in one day', 'Test 4 is opening on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2680, 13, 'vmsroot', 'vmsroot', 'vmsroot', 'open', '1Day', '0000-00-00', 'sent', 'Test 4 open date is coming up in one day', 'Test 4 is opening on 2024-11-12', '2024-11-11 19:33:59', 1, 2),
(2681, 13, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'due', '1Day', '0000-00-00', 'sent', 'Test 4 due date is coming up in one day', 'Test 4 is due on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2682, 13, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'due', '1Day', '0000-00-00', 'sent', 'Test 4 due date is coming up in one day', 'Test 4 is due on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2683, 13, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'due', '1Day', '0000-00-00', 'sent', 'Test 4 due date is coming up in one day', 'Test 4 is due on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2684, 13, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'due', '1Day', '0000-00-00', 'sent', 'Test 4 due date is coming up in one day', 'Test 4 is due on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2685, 13, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'due', '1Day', '0000-00-00', 'sent', 'Test 4 due date is coming up in one day', 'Test 4 is due on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2686, 13, 'vmsroot', 'vmsroot', 'polack@um.edu', 'due', '1Day', '0000-00-00', 'sent', 'Test 4 due date is coming up in one day', 'Test 4 is due on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2687, 13, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'due', '1Day', '0000-00-00', 'sent', 'Test 4 due date is coming up in one day', 'Test 4 is due on 2024-11-12', '2024-11-11 19:33:59', 0, 2),
(2688, 13, 'vmsroot', 'vmsroot', 'vmsroot', 'due', '1Day', '0000-00-00', 'sent', 'Test 4 due date is coming up in one day', 'Test 4 is due on 2024-11-12', '2024-11-11 19:33:59', 1, 2),
(5593, 16, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test open date is coming up in one day', 'Testing for Notifications 3 wasRead test is opening on 2024-11-12', '2024-11-11 20:01:31', 0, 2),
(5594, 16, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test open date is coming up in one day', 'Testing for Notifications 3 wasRead test is opening on 2024-11-12', '2024-11-11 20:01:31', 0, 2),
(5595, 16, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test open date is coming up in one day', 'Testing for Notifications 3 wasRead test is opening on 2024-11-12', '2024-11-11 20:01:31', 0, 2),
(5596, 16, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test open date is coming up in one day', 'Testing for Notifications 3 wasRead test is opening on 2024-11-12', '2024-11-11 20:01:31', 0, 2),
(5597, 16, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test open date is coming up in one day', 'Testing for Notifications 3 wasRead test is opening on 2024-11-12', '2024-11-11 20:01:31', 0, 2),
(5598, 16, 'vmsroot', 'vmsroot', 'polack@um.edu', 'open', '1Day', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test open date is coming up in one day', 'Testing for Notifications 3 wasRead test is opening on 2024-11-12', '2024-11-11 20:01:31', 0, 2),
(5599, 16, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test open date is coming up in one day', 'Testing for Notifications 3 wasRead test is opening on 2024-11-12', '2024-11-11 20:01:31', 0, 2),
(5600, 16, 'vmsroot', 'vmsroot', 'vmsroot', 'open', '1Day', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test open date is coming up in one day', 'Testing for Notifications 3 wasRead test is opening on 2024-11-12', '2024-11-11 20:01:31', 1, 2),
(9137, 11, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 2 open date is coming up in one day', 'Test 2 is opening on 2024-11-12', '2024-11-11 21:04:41', 0, 2),
(9138, 11, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 2 open date is coming up in one day', 'Test 2 is opening on 2024-11-12', '2024-11-11 21:04:41', 0, 2),
(9139, 11, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 2 open date is coming up in one day', 'Test 2 is opening on 2024-11-12', '2024-11-11 21:04:41', 0, 2),
(9140, 11, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 2 open date is coming up in one day', 'Test 2 is opening on 2024-11-12', '2024-11-11 21:04:41', 0, 2),
(9141, 11, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 2 open date is coming up in one day', 'Test 2 is opening on 2024-11-12', '2024-11-11 21:04:41', 0, 2),
(9142, 11, 'vmsroot', 'vmsroot', 'polack@um.edu', 'open', '1Day', '0000-00-00', 'sent', 'Test 2 open date is coming up in one day', 'Test 2 is opening on 2024-11-12', '2024-11-11 21:04:41', 0, 2),
(9143, 11, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'open', '1Day', '0000-00-00', 'sent', 'Test 2 open date is coming up in one day', 'Test 2 is opening on 2024-11-12', '2024-11-11 21:04:42', 0, 2),
(9144, 11, 'vmsroot', 'vmsroot', 'vmsroot', 'open', '1Day', '0000-00-00', 'sent', 'Test 2 open date is coming up in one day', 'Test 2 is opening on 2024-11-12', '2024-11-11 21:04:42', 1, 2),
(13793, 11, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 2 due date is coming up in one month', 'Test 2 is due on 2024-12-09', '2024-11-11 22:11:50', 0, 1),
(13794, 11, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 2 due date is coming up in one month', 'Test 2 is due on 2024-12-09', '2024-11-11 22:11:50', 0, 1),
(13795, 11, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 2 due date is coming up in one month', 'Test 2 is due on 2024-12-09', '2024-11-11 22:11:50', 0, 1),
(13796, 11, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 2 due date is coming up in one month', 'Test 2 is due on 2024-12-09', '2024-11-11 22:11:50', 0, 1),
(13797, 11, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 2 due date is coming up in one month', 'Test 2 is due on 2024-12-09', '2024-11-11 22:11:50', 0, 1),
(13798, 11, 'vmsroot', 'vmsroot', 'polack@um.edu', 'due', '1Month', '0000-00-00', 'sent', 'Test 2 due date is coming up in one month', 'Test 2 is due on 2024-12-09', '2024-11-11 22:11:50', 0, 1),
(13799, 11, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 2 due date is coming up in one month', 'Test 2 is due on 2024-12-09', '2024-11-11 22:11:50', 0, 1),
(13800, 11, 'vmsroot', 'vmsroot', 'vmsroot', 'due', '1Month', '0000-00-00', 'sent', 'Test 2 due date is coming up in one month', 'Test 2 is due on 2024-12-09', '2024-11-11 22:11:50', 0, 1),
(13801, 12, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 3 has OPENED', 'Test 3 opened on 2024-10-26', '2024-11-11 22:11:50', 0, 3),
(13802, 12, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 3 has OPENED', 'Test 3 opened on 2024-10-26', '2024-11-11 22:11:50', 0, 3),
(13803, 12, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 3 has OPENED', 'Test 3 opened on 2024-10-26', '2024-11-11 22:11:50', 0, 3),
(13804, 12, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 3 has OPENED', 'Test 3 opened on 2024-10-26', '2024-11-11 22:11:50', 0, 3),
(13805, 12, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 3 has OPENED', 'Test 3 opened on 2024-10-26', '2024-11-11 22:11:50', 0, 3),
(13806, 12, 'vmsroot', 'vmsroot', 'polack@um.edu', 'open', 'late', '0000-00-00', 'sent', 'Test 3 has OPENED', 'Test 3 opened on 2024-10-26', '2024-11-11 22:11:50', 0, 3),
(13807, 12, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 3 has OPENED', 'Test 3 opened on 2024-10-26', '2024-11-11 22:11:50', 0, 3),
(13808, 12, 'vmsroot', 'vmsroot', 'vmsroot', 'open', 'late', '0000-00-00', 'sent', 'Test 3 has OPENED', 'Test 3 opened on 2024-10-26', '2024-11-11 22:11:50', 0, 3),
(13809, 12, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'due', 'late', '0000-00-00', 'sent', 'Test 3 was DUE', 'Test 3 was due on 2024-10-31', '2024-11-11 22:11:50', 0, 3),
(13810, 12, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'due', 'late', '0000-00-00', 'sent', 'Test 3 was DUE', 'Test 3 was due on 2024-10-31', '2024-11-11 22:11:50', 0, 3),
(13811, 12, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'due', 'late', '0000-00-00', 'sent', 'Test 3 was DUE', 'Test 3 was due on 2024-10-31', '2024-11-11 22:11:50', 0, 3),
(13812, 12, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'due', 'late', '0000-00-00', 'sent', 'Test 3 was DUE', 'Test 3 was due on 2024-10-31', '2024-11-11 22:11:50', 0, 3),
(13813, 12, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'due', 'late', '0000-00-00', 'sent', 'Test 3 was DUE', 'Test 3 was due on 2024-10-31', '2024-11-11 22:11:50', 0, 3),
(13814, 12, 'vmsroot', 'vmsroot', 'polack@um.edu', 'due', 'late', '0000-00-00', 'sent', 'Test 3 was DUE', 'Test 3 was due on 2024-10-31', '2024-11-11 22:11:50', 0, 3),
(13815, 12, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'due', 'late', '0000-00-00', 'sent', 'Test 3 was DUE', 'Test 3 was due on 2024-10-31', '2024-11-11 22:11:50', 0, 3),
(13816, 12, 'vmsroot', 'vmsroot', 'vmsroot', 'due', 'late', '0000-00-00', 'sent', 'Test 3 was DUE', 'Test 3 was due on 2024-10-31', '2024-11-11 22:11:50', 0, 3),
(13817, 14, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 5 has OPENED', 'Test 5 opened on 2024-11-11', '2024-11-11 22:11:50', 0, 3),
(13818, 14, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 5 has OPENED', 'Test 5 opened on 2024-11-11', '2024-11-11 22:11:50', 0, 3),
(13819, 14, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 5 has OPENED', 'Test 5 opened on 2024-11-11', '2024-11-11 22:11:50', 0, 3),
(13820, 14, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 5 has OPENED', 'Test 5 opened on 2024-11-11', '2024-11-11 22:11:50', 0, 3),
(13821, 14, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 5 has OPENED', 'Test 5 opened on 2024-11-11', '2024-11-11 22:11:50', 0, 3),
(13822, 14, 'vmsroot', 'vmsroot', 'polack@um.edu', 'open', 'late', '0000-00-00', 'sent', 'Test 5 has OPENED', 'Test 5 opened on 2024-11-11', '2024-11-11 22:11:50', 0, 3),
(13823, 14, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 5 has OPENED', 'Test 5 opened on 2024-11-11', '2024-11-11 22:11:50', 0, 3),
(13824, 14, 'vmsroot', 'vmsroot', 'vmsroot', 'open', 'late', '0000-00-00', 'sent', 'Test 5 has OPENED', 'Test 5 opened on 2024-11-11', '2024-11-11 22:11:50', 0, 3),
(13825, 14, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'due', '6Months', '0000-00-00', 'sent', 'Test 5 due date is coming up in six months', 'Test 5 is due on 2025-01-31', '2024-11-11 22:11:50', 0, 1),
(13826, 14, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'due', '6Months', '0000-00-00', 'sent', 'Test 5 due date is coming up in six months', 'Test 5 is due on 2025-01-31', '2024-11-11 22:11:50', 0, 1),
(13827, 14, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'due', '6Months', '0000-00-00', 'sent', 'Test 5 due date is coming up in six months', 'Test 5 is due on 2025-01-31', '2024-11-11 22:11:50', 0, 1),
(13828, 14, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'due', '6Months', '0000-00-00', 'sent', 'Test 5 due date is coming up in six months', 'Test 5 is due on 2025-01-31', '2024-11-11 22:11:50', 0, 1),
(13829, 14, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'due', '6Months', '0000-00-00', 'sent', 'Test 5 due date is coming up in six months', 'Test 5 is due on 2025-01-31', '2024-11-11 22:11:50', 0, 1),
(13830, 14, 'vmsroot', 'vmsroot', 'polack@um.edu', 'due', '6Months', '0000-00-00', 'sent', 'Test 5 due date is coming up in six months', 'Test 5 is due on 2025-01-31', '2024-11-11 22:11:50', 0, 1),
(13831, 14, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'due', '6Months', '0000-00-00', 'sent', 'Test 5 due date is coming up in six months', 'Test 5 is due on 2025-01-31', '2024-11-11 22:11:50', 0, 1),
(13832, 14, 'vmsroot', 'vmsroot', 'vmsroot', 'due', '6Months', '0000-00-00', 'sent', 'Test 5 due date is coming up in six months', 'Test 5 is due on 2025-01-31', '2024-11-11 22:11:50', 0, 1),
(13833, 15, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 6 has OPENED', 'Test 6 opened on 2024-11-05', '2024-11-11 22:11:50', 0, 3),
(13834, 15, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 6 has OPENED', 'Test 6 opened on 2024-11-05', '2024-11-11 22:11:50', 0, 3),
(13835, 15, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 6 has OPENED', 'Test 6 opened on 2024-11-05', '2024-11-11 22:11:50', 0, 3),
(13836, 15, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 6 has OPENED', 'Test 6 opened on 2024-11-05', '2024-11-11 22:11:50', 0, 3),
(13837, 15, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 6 has OPENED', 'Test 6 opened on 2024-11-05', '2024-11-11 22:11:50', 0, 3),
(13838, 15, 'vmsroot', 'vmsroot', 'polack@um.edu', 'open', 'late', '0000-00-00', 'sent', 'Test 6 has OPENED', 'Test 6 opened on 2024-11-05', '2024-11-11 22:11:50', 0, 3),
(13839, 15, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'open', 'late', '0000-00-00', 'sent', 'Test 6 has OPENED', 'Test 6 opened on 2024-11-05', '2024-11-11 22:11:50', 0, 3),
(13840, 15, 'vmsroot', 'vmsroot', 'vmsroot', 'open', 'late', '0000-00-00', 'sent', 'Test 6 has OPENED', 'Test 6 opened on 2024-11-05', '2024-11-11 22:11:50', 0, 3),
(13841, 15, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 6 due date is coming up in one month', 'Test 6 is due on 2024-11-30', '2024-11-11 22:11:50', 0, 1),
(13842, 15, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 6 due date is coming up in one month', 'Test 6 is due on 2024-11-30', '2024-11-11 22:11:50', 0, 1),
(13843, 15, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 6 due date is coming up in one month', 'Test 6 is due on 2024-11-30', '2024-11-11 22:11:50', 0, 1),
(13844, 15, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 6 due date is coming up in one month', 'Test 6 is due on 2024-11-30', '2024-11-11 22:11:50', 0, 1),
(13845, 15, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 6 due date is coming up in one month', 'Test 6 is due on 2024-11-30', '2024-11-11 22:11:50', 0, 1),
(13846, 15, 'vmsroot', 'vmsroot', 'polack@um.edu', 'due', '1Month', '0000-00-00', 'sent', 'Test 6 due date is coming up in one month', 'Test 6 is due on 2024-11-30', '2024-11-11 22:11:50', 0, 1),
(13847, 15, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'due', '1Month', '0000-00-00', 'sent', 'Test 6 due date is coming up in one month', 'Test 6 is due on 2024-11-30', '2024-11-11 22:11:50', 0, 1),
(13848, 15, 'vmsroot', 'vmsroot', 'vmsroot', 'due', '1Month', '0000-00-00', 'sent', 'Test 6 due date is coming up in one month', 'Test 6 is due on 2024-11-30', '2024-11-11 22:11:50', 0, 1),
(13849, 16, 'vmsroot', 'vmsroot', 'brianna@gmail.com', 'due', '1Week', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test due date is coming up in one week', 'Testing for Notifications 3 wasRead test is due on 2024-11-18', '2024-11-11 22:11:50', 0, 2),
(13850, 16, 'vmsroot', 'vmsroot', 'bum@gmail.com', 'due', '1Week', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test due date is coming up in one week', 'Testing for Notifications 3 wasRead test is due on 2024-11-18', '2024-11-11 22:11:50', 0, 2),
(13851, 16, 'vmsroot', 'vmsroot', 'mom@gmail.com', 'due', '1Week', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test due date is coming up in one week', 'Testing for Notifications 3 wasRead test is due on 2024-11-18', '2024-11-11 22:11:50', 0, 2),
(13852, 16, 'vmsroot', 'vmsroot', 'oliver@gmail.com', 'due', '1Week', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test due date is coming up in one week', 'Testing for Notifications 3 wasRead test is due on 2024-11-18', '2024-11-11 22:11:50', 0, 2),
(13853, 16, 'vmsroot', 'vmsroot', 'peter@gmail.com', 'due', '1Week', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test due date is coming up in one week', 'Testing for Notifications 3 wasRead test is due on 2024-11-18', '2024-11-11 22:11:50', 0, 2),
(13854, 16, 'vmsroot', 'vmsroot', 'polack@um.edu', 'due', '1Week', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test due date is coming up in one week', 'Testing for Notifications 3 wasRead test is due on 2024-11-18', '2024-11-11 22:11:50', 0, 2),
(13855, 16, 'vmsroot', 'vmsroot', 'tom@gmail.com', 'due', '1Week', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test due date is coming up in one week', 'Testing for Notifications 3 wasRead test is due on 2024-11-18', '2024-11-11 22:11:50', 0, 2),
(13856, 16, 'vmsroot', 'vmsroot', 'vmsroot', 'due', '1Week', '0000-00-00', 'sent', 'Testing for Notifications 3 wasRead test due date is coming up in one week', 'Testing for Notifications 3 wasRead test is due on 2024-11-18', '2024-11-11 22:11:50', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `dbpersons`
--

CREATE TABLE `dbpersons` (
  `id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` text DEFAULT NULL,
  `venue` text DEFAULT NULL,
  `first_name` text NOT NULL,
  `last_name` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip` text DEFAULT NULL,
  `phone1` varchar(12) NOT NULL,
  `phone1type` text DEFAULT NULL,
  `phone2` varchar(12) DEFAULT NULL,
  `phone2type` text DEFAULT NULL,
  `birthday` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `contact_name` text NOT NULL,
  `contact_num` varchar(12) NOT NULL,
  `relation` text NOT NULL,
  `contact_time` text NOT NULL,
  `cMethod` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `availability` text DEFAULT NULL,
  `schedule` text DEFAULT NULL,
  `hours` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `sundays_start` char(5) DEFAULT NULL,
  `sundays_end` char(5) DEFAULT NULL,
  `mondays_start` char(5) DEFAULT NULL,
  `mondays_end` char(5) DEFAULT NULL,
  `tuesdays_start` char(5) DEFAULT NULL,
  `tuesdays_end` char(5) DEFAULT NULL,
  `wednesdays_start` char(5) DEFAULT NULL,
  `wednesdays_end` char(5) DEFAULT NULL,
  `thursdays_start` char(5) DEFAULT NULL,
  `thursdays_end` char(5) DEFAULT NULL,
  `fridays_start` char(5) DEFAULT NULL,
  `fridays_end` char(5) DEFAULT NULL,
  `saturdays_start` char(5) DEFAULT NULL,
  `saturdays_end` char(5) DEFAULT NULL,
  `profile_pic` text NOT NULL,
  `force_password_change` tinyint(1) NOT NULL,
  `gender` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dbpersons`
--

INSERT INTO `dbpersons` (`id`, `start_date`, `venue`, `first_name`, `last_name`, `address`, `city`, `state`, `zip`, `phone1`, `phone1type`, `phone2`, `phone2type`, `birthday`, `email`, `contact_name`, `contact_num`, `relation`, `contact_time`, `cMethod`, `type`, `status`, `availability`, `schedule`, `hours`, `notes`, `password`, `sundays_start`, `sundays_end`, `mondays_start`, `mondays_end`, `tuesdays_start`, `tuesdays_end`, `wednesdays_start`, `wednesdays_end`, `thursdays_start`, `thursdays_end`, `fridays_start`, `fridays_end`, `saturdays_start`, `saturdays_end`, `profile_pic`, `force_password_change`, `gender`) VALUES
('brianna@gmail.com', '2024-01-22', 'portland', 'Brianna', 'Wahl', '212 Latham Road', 'Mineola', 'VA', '11501', '1234567890', 'cellphone', '', '', '2004-04-04', 'brianna@gmail.com', 'Mom', '1234567890', 'Mother', 'Days', 'text', 'admin', 'Active', '', '', '', '', '$2y$10$jNbMmZwq.1r/5/oy61IRkOSX4PY6sxpYEdWfu9tLRZA6m1NgsxD6m', '00:00', '10:00', '', '', '', '', '02:00', '16:00', '', '', '', '', '', '', '', 0, 'Female'),
('bum@gmail.com', '2024-01-24', 'portland', 'bum', 'bum', '1345 Strattford St.', 'Mineola', 'VA', '22401', '1234567890', 'home', '', '', '1111-11-11', 'bum@gmail.com', 'Mom', '1234567890', 'Mom', 'Mornings', 'text', 'admin', 'Active', '', '', '', '', '$2y$10$Ps8FnZXT7d4uiU/R5YFnRecIRbRakyVtbXP9TVqp7vVpuB3yTXFIO', '', '', '15:00', '18:00', '', '', '', '', '', '', '', '', '', '', '', 0, 'Male'),
('mom@gmail.com', '2024-01-22', 'portland', 'Lorraine', 'Egan', '212 Latham Road', 'Mineola', 'NY', '11501', '5167423832', 'home', '', '', '1910-10-10', 'mom@gmail.com', 'Mom', '5167423832', 'Dead', 'Never', 'phone', 'admin', 'Active', '', '', '', '', '$2y$10$of1CkoNXZwyhAMS5GQ.aYuAW1SHptF6z31ONahnF2qK4Y/W9Ty2h2', '00:00', '10:00', '18:00', '19:00', '06:00', '14:00', '02:00', '12:00', '02:00', '16:00', '12:00', '18:00', '08:00', '17:00', '', 0, 'Male'),
('oliver@gmail.com', '2024-01-22', 'portland', 'Oliver', 'Wahl', '1345 Strattford St.', 'Fredericksburg', 'VA', '22401', '1234567890', 'home', '', '', '2011-11-11', 'oliver@gmail.com', 'Mom', '1234567890', 'Mother', 'Middle of the Night', 'text', 'admin', 'Active', '', '', '', '', '$2y$10$tgIjMkXhPzdmgGhUgbfPRuXLJVZHLiC0pWQQwOYKx8p8H8XY3eHw6', '06:00', '14:00', '', '', '', '', '', '', '', '', '', '', '04:00', '18:00', '', 0, 'Other'),
('peter@gmail.com', '2024-01-22', 'portland', 'Peter', 'Polack', '1345 Strattford St.', 'Mineola', 'VA', '12345', '1234567890', 'cellphone', '', '', '1968-09-09', 'peter@gmail.com', 'Mom', '1234567890', 'Mom', 'Don&amp;amp;#039;t Call or Text or Email', 'email', 'admin', 'Active', '', '', '', '', '$2y$10$j5xJ6GWaBhnb45aktS.kruk05u./TsAhEoCI3VRlNs0SRGrIqz.B6', '00:00', '19:00', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 'Male'),
('polack@um.edu', '2024-01-22', 'portland', 'Jennifer', 'Polack', '15 Wallace Farms Lane', 'Fredericksburg', 'VA', '22406', '1234567890', 'cellphone', '', '', '1970-05-01', 'polack@um.edu', 'Mom', '1234567890', 'Mom', 'Days', 'email', 'admin', 'Active', '', '', '', '', '$2y$10$mp18j4WqhlQo7MTeS/9kt.i08n7nbt0YMuRoAxtAy52BlinqPUE4C', '00:00', '12:00', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 'Female'),
('tom@gmail.com', '2024-01-22', 'portland', 'tom', 'tom', '1345 Strattford St.', 'Mineola', 'NY', '12345', '1234567890', 'home', '', '', '1920-02-02', 'tom@gmail.com', 'Dad', '9876543210', 'Father', 'Mornings', 'phone', 'admin', 'Active', '', '', '', '', '$2y$10$1Zcj7n/prdkNxZjxTK1zUOF7391byZvsXkJcN8S8aZL57sz/OfxP.', '11:00', '17:00', '', '', '11:00', '14:00', '', '', '09:00', '14:00', '', '', '', '', '', 0, 'Male'),
('vmsroot', 'N/A', 'portland', 'vmsroot', '', 'N/A', 'N/A', 'VA', 'N/A', '', 'N/A', 'N/A', 'N/A', 'N/A', 'vmsroot', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbmessages`
--
ALTER TABLE `dbmessages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grant_id` (`grant_id`),
  ADD KEY `person_id` (`person_id`);

--
-- AUTO_INCREMENT for table `dbmessages`
--
ALTER TABLE `dbmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13857;

--
-- Indexes for table `dbevents`
--
ALTER TABLE `dbevents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbfields`
--
ALTER TABLE `dbfields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbgrantfields`
--
ALTER TABLE `dbgrantfields`
  ADD KEY `fk_grant_id` (`grant_id`),
  ADD KEY `fk_field_id` (`field_id`);

--
-- Indexes for table `dbgrantlinks`
--
ALTER TABLE `dbgrantlinks`
  ADD KEY `fk_grant_id` (`grant_id`),
  ADD KEY `fk_link_id` (`link_id`);

--
-- Indexes for table `dblinks`
--
ALTER TABLE `dblinks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbpersons`
--
ALTER TABLE `dbpersons`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbevents`
--
ALTER TABLE `dbevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `dbfields`
--
ALTER TABLE `dbfields`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dblinks`
--
ALTER TABLE `dblinks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dbgrantlinks`
--
ALTER TABLE `dbgrantlinks`
  ADD CONSTRAINT `fk_grant_id` FOREIGN KEY (`grant_id`) REFERENCES `dbevents` (`id`),
  ADD CONSTRAINT `fk_link_id` FOREIGN KEY (`link_id`) REFERENCES `dblinks` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Constraints for table `dbmessages`
--
ALTER TABLE `dbmessages`
  ADD CONSTRAINT `dbmessages_ibfk_1` FOREIGN KEY (`grant_id`) REFERENCES `dbevents` (`id`),
  ADD CONSTRAINT `dbmessages_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `dbpersons` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
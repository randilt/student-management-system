-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 26, 2025 at 03:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

-- Create database
CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `student_id`, `stream_id`, `status`, `applied_at`, `reviewed_by`, `reviewed_at`, `comments`) VALUES
(1, 1, 2, 'rejected', '2025-03-25 13:25:43', 1, '2025-03-25 13:47:40', 'Already selected for one'),
(2, 1, 4, 'approved', '2025-03-25 13:46:08', 1, '2025-03-25 13:47:16', 'Good!, Accepted thank you for applying'),
(3, 2, 2, 'rejected', '2025-03-25 16:22:35', 3, '2025-03-26 13:15:16', 'test'),
(4, 3, 2, 'approved', '2025-03-25 17:20:47', 3, '2025-03-26 13:15:09', 'test'),
(5, 4, 2, 'approved', '2025-03-26 13:11:32', 1, '2025-03-26 13:12:30', 'Good application!'),
(6, 5, 2, 'approved', '2025-03-26 13:22:00', 1, '2025-03-26 13:22:59', 'Approved!!!!'),
(7, 5, 4, 'rejected', '2025-03-26 13:22:09', 1, '2025-03-26 13:23:15', 'Rejected,,!!'),
(8, 5, 1, 'pending', '2025-03-26 13:22:14', NULL, NULL, NULL),
(9, 6, 2, 'pending', '2025-03-26 14:16:41', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `application_subjects`
--

CREATE TABLE `application_subjects` (
  `application_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_subjects`
--

INSERT INTO `application_subjects` (`application_id`, `subject_id`) VALUES
(1, 4),
(1, 5),
(1, 6),
(2, 10),
(2, 11),
(2, 12),
(3, 4),
(3, 5),
(3, 6),
(4, 4),
(4, 5),
(4, 18),
(5, 4),
(5, 5),
(5, 18),
(6, 4),
(6, 5),
(6, 18),
(7, 10),
(7, 12),
(7, 13),
(8, 1),
(8, 2),
(8, 3),
(9, 4),
(9, 6),
(9, 18);

-- --------------------------------------------------------

--
-- Table structure for table `basket_subjects`
--

CREATE TABLE `basket_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `grade` enum('A','B','C','S','W','F') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basket_subjects`
--

INSERT INTO `basket_subjects` (`id`, `student_id`, `subject_name`, `grade`) VALUES
(1, 6, 'ICT', 'A'),
(2, 6, 'Business Studies', 'B'),
(3, 6, 'Health Science', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `ol_results`
--

CREATE TABLE `ol_results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `grade` enum('A','B','C','S','W','F') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ol_results`
--

INSERT INTO `ol_results` (`id`, `student_id`, `subject`, `grade`) VALUES
(1, 1, 'Mathematics', 'A'),
(2, 1, 'Science', 'A'),
(3, 1, 'English', 'A'),
(4, 1, 'Sinhala', 'A'),
(5, 1, 'History', 'A'),
(6, 1, 'Religion', 'A'),
(7, 1, 'ICT', 'A'),
(8, 1, 'Commerce', 'A'),
(9, 1, 'Geography', 'A'),
(10, 2, 'Mathematics', 'A'),
(11, 2, 'Science', 'B'),
(12, 2, 'English', 'S'),
(13, 2, 'Sinhala', 'A'),
(14, 2, 'History', 'W'),
(15, 2, 'Religion', 'A'),
(16, 2, 'ICT', 'B'),
(17, 2, 'Commerce', 'C'),
(18, 2, 'Geography', 'S'),
(19, 3, 'Mathematics', 'A'),
(20, 3, 'Science', 'B'),
(21, 3, 'English', 'A'),
(22, 3, 'Sinhala', 'A'),
(23, 3, 'History', 'B'),
(24, 3, 'Religion', 'C'),
(25, 3, 'ICT', 'A'),
(26, 3, 'Commerce', 'C'),
(27, 3, 'Geography', 'A'),
(28, 4, 'Mathematics', 'A'),
(29, 4, 'Science', 'C'),
(30, 4, 'English', 'W'),
(31, 4, 'Sinhala', 'B'),
(32, 4, 'History', 'C'),
(33, 4, 'Religion', 'S'),
(34, 4, 'ICT', 'W'),
(35, 4, 'Commerce', 'A'),
(36, 4, 'Geography', 'W'),
(37, 5, 'Mathematics', 'A'),
(38, 5, 'Science', 'B'),
(39, 5, 'English', 'C'),
(40, 5, 'Sinhala', 'S'),
(41, 5, 'History', 'A'),
(42, 5, 'Religion', 'B'),
(43, 5, 'ICT', 'C'),
(44, 5, 'Commerce', 'S'),
(45, 5, 'Geography', 'W'),
(46, 6, 'Sinhala', 'A'),
(47, 6, 'Mathematics', 'B'),
(48, 6, 'Science', 'A'),
(49, 6, 'English', 'C'),
(50, 6, 'History', 'A'),
(51, 6, 'Religion', 'S');

-- --------------------------------------------------------

--
-- Table structure for table `streams`
--

CREATE TABLE `streams` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `head_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `streams`
--

INSERT INTO `streams` (`id`, `name`, `description`, `head_user_id`) VALUES
(1, 'Bio Science', 'Biology, Chemistry, Physics, ICT and related subjects', 2),
(2, 'Mathematics', 'Combined Mathematics, Physics, Chemistry and related subjects', 3),
(3, 'Commerce', 'Business Studies, Economics, Accounting and related subjects', 4),
(4, 'Arts', 'Languages, History, Geography and related subjects', 5),
(5, 'Technology', 'Engineering Technology, Science for Technology and related subjects', 11),
(7, 'Test stream', 'asdasdasdasd', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `parent_name` varchar(100) NOT NULL,
  `parent_contact` varchar(15) NOT NULL,
  `index_number` varchar(50) DEFAULT NULL,
  `nic_number` varchar(20) DEFAULT NULL,
  `ol_exam_year` year(4) DEFAULT NULL,
  `preferred_stream_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `address`, `contact_number`, `parent_name`, `parent_contact`, `index_number`, `nic_number`, `ol_exam_year`, `preferred_stream_id`) VALUES
(1, 7, 'Withanage', 'Withana', '2003-11-22', 'male', '6/8, Rohitha Weerakoon Mawatha, Katulanda, Dekatana\r\nRohitha Weerakon Mawatha, Katulanda, Dekatana', '0781347983', 'Priyanka', '0777511440', NULL, NULL, NULL, NULL),
(2, 10, 'Kavindu', 'Perera', '2004-02-03', 'male', 'Kavindu, 56/a, colombo', '7841235123', 'Mala', '012546984', NULL, NULL, NULL, NULL),
(3, 13, 'Kevin', 'Jayarathna', '2000-06-06', 'male', '2/8, Colombo, Sri Lanka', '0777411441', 'Jayarathna', '0114452369', '2145236', '20001214536', '2019', 2),
(4, 14, 'John', 'Doe', '2003-01-01', 'male', '6/8, Rohitha Weerakoon Mawatha, Katulanda, Dekatana\r\nRohitha Weerakon Mawatha, Katulanda, Dekatana', '0781347983', 'Priyanka', '0777511440', '12454852', '784153365', '2019', 2),
(5, 17, 'John', 'Doe', '2004-11-01', 'male', 'Test address', '01141254632', 'Test name', '0777411552', '321321321', '321123321123', '2000', 3),
(6, 19, 'Jane', 'Smith', '1998-06-10', 'female', 'No. 511/23, Jane Street', '01141254632', 'Test name', '0777411552', '4545454', '321123321123', '2001', 4);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `stream_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `stream_id`) VALUES
(1, 'Biology', 1),
(2, 'Chemistry', 1),
(3, 'Physics', 1),
(4, 'Combined Mathematics', 2),
(5, 'Physics', 2),
(6, 'Chemistry', 2),
(7, 'Business Studies', 3),
(8, 'Economics', 3),
(9, 'Accounting', 3),
(10, 'History 1', 4),
(11, 'Geography', 4),
(12, 'Political Science', 4),
(13, 'Languages', 4),
(14, 'Engineering Technology', 5),
(15, 'Science for Technology', 5),
(16, 'Information Communication Technology', 5),
(18, 'Information Communication Technology (ICT)', 2),
(20, 'Test subject 2222222222222', 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('student','principal','stream_head','administrator') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `account_status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`, `account_status`) VALUES
(1, 'principal', '$2a$12$jXEJ00JxEPl0l5LaDOP1mOd7H2YHN7WOBXIwGgHVxCNwegMxhX052', 'principal@school.edu', 'principal', '2025-03-25 12:54:19', 'active'),
(2, 'biohead', '$2a$12$jXEJ00JxEPl0l5LaDOP1mOd7H2YHN7WOBXIwGgHVxCNwegMxhX052', 'biohead@school.edu', 'stream_head', '2025-03-25 12:54:19', 'active'),
(3, 'mathhead', '$2a$12$jXEJ00JxEPl0l5LaDOP1mOd7H2YHN7WOBXIwGgHVxCNwegMxhX052', 'mathhead@school.edu', 'stream_head', '2025-03-25 12:54:19', 'active'),
(4, 'comhead', '$2a$12$jXEJ00JxEPl0l5LaDOP1mOd7H2YHN7WOBXIwGgHVxCNwegMxhX052', 'comhead@school.edu', 'stream_head', '2025-03-25 12:54:19', 'active'),
(5, 'artshead', '$2a$12$jXEJ00JxEPl0l5LaDOP1mOd7H2YHN7WOBXIwGgHVxCNwegMxhX052', 'artshead@school.edu', 'stream_head', '2025-03-25 12:54:19', 'active'),
(7, 'randiltharusha', '$2y$10$nLjtOdGdBOTnqBvbdYMs/ePcj3Ge8ye1aGaHPVnMYASgwzJJJ8YUm', 'randiltharusha72@gmail.com', 'student', '2025-03-25 13:04:42', 'active'),
(9, 'Admin2', '$2y$10$h9aXekxZ2VA8evCd2TPCseJ72OyYRG50bOpyDxp.5pa1cGltBiG0O', 'test@asd.com', 'administrator', '2025-03-25 15:43:26', 'active'),
(10, 'Kavindu', '$2y$10$y8e2FLxLFWOIZ1mvk5Q/H.72CmNHavuxTbNHPLVJ2yK.3N.SB9do6', 'kavindu@gmail.com', 'student', '2025-03-25 16:21:57', 'active'),
(11, 'techhead', '$2y$10$jCiIj7o2iynwMprQ9jEpPeiR2tUVo9BAbO1ohk3koMe3pCL1anYkq', 'techhead@gmail.com', 'stream_head', '2025-03-25 16:30:45', 'active'),
(12, 'admin3', '$2y$10$PCrSldTFQXYFVvQo7Ie8iuzn6pInMUcthvo306DpB/CeLFeOiIcI2', 'admin3@gmail.com', 'administrator', '2025-03-25 16:57:05', 'active'),
(13, 'Kevin', '$2y$10$eMxUYBva.29/lK2glA49GeEJ9YR6UUJONr2WRis7oM5nBOc64ECNS', 'kevin@gg.com', 'student', '2025-03-25 17:20:23', 'active'),
(14, 'John', '$2y$10$opcenvqATJszwCdjRILwn.q93PzpGLCNllBfrvRgDsSRFQmSp9.sK', 'john@example.com', 'student', '2025-03-26 13:10:35', 'active'),
(15, 'teststreamhead', '$2y$10$3ygs25Dt.Kt5WCBAGqi0e.ZsUTYmK77PCB/SZn1YcTURUvImsSXj.', 'teststreamhead@email.com', 'stream_head', '2025-03-26 13:13:36', 'active'),
(16, 'admin123', '$2y$10$qoMYg0nS2/by0mPT4l5.Wup1VpQwdgs/HMmvtfFIsgGCyeghVtIMG', 'admin123@gmail.com', 'administrator', '2025-03-26 13:15:55', 'active'),
(17, 'johndoe', '$2y$10$vKFH6OlfDsePKbMSDWcz2ul3Wqq7bhdKMoIS0N2j.CSp.Lm5JvTY2', 'johndoe@gmail.com', 'student', '2025-03-26 13:21:30', 'active'),
(19, 'janesmith', '$2y$10$jgj3bfAC7vvpap.45myyueNRLUOym2OwC9KdSJCGSwsAqifZLoB9q', 'jane@jane.com', 'student', '2025-03-26 14:16:23', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `stream_id` (`stream_id`),
  ADD KEY `reviewed_by` (`reviewed_by`);

--
-- Indexes for table `application_subjects`
--
ALTER TABLE `application_subjects`
  ADD PRIMARY KEY (`application_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `basket_subjects`
--
ALTER TABLE `basket_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `ol_results`
--
ALTER TABLE `ol_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `streams`
--
ALTER TABLE `streams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `head_user_id` (`head_user_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `preferred_stream_id` (`preferred_stream_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stream_id` (`stream_id`);

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
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `basket_subjects`
--
ALTER TABLE `basket_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ol_results`
--
ALTER TABLE `ol_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `streams`
--
ALTER TABLE `streams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_3` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `application_subjects`
--
ALTER TABLE `application_subjects`
  ADD CONSTRAINT `application_subjects_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `application_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `basket_subjects`
--
ALTER TABLE `basket_subjects`
  ADD CONSTRAINT `basket_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ol_results`
--
ALTER TABLE `ol_results`
  ADD CONSTRAINT `ol_results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `streams`
--
ALTER TABLE `streams`
  ADD CONSTRAINT `streams_ibfk_1` FOREIGN KEY (`head_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`preferred_stream_id`) REFERENCES `streams` (`id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

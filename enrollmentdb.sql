-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 06:08 PM
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
-- Database: `enrollmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `unit` float NOT NULL,
  `semester` varchar(50) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `program_id`, `course_code`, `title`, `unit`, `semester`, `year`) VALUES
(2, 1, 'RLW', 'Rizal\'s Lifes and Works of Pangi ', 3, '1st Sem', 1),
(3, 2, 'ABC', 'Angel MVC', 3, '1st Sem', 1),
(4, 3, 'DSA', 'Data Structures', 3, '1st Sem', 1),
(6, 1, 'OOP', 'Object Oriented Programming', 3, '1st Sem', 1),
(7, 3, 'BSD', 'Benjamin S David', 3, '1st Sem', 3),
(8, 1, 'IPT', 'APTAPT', 3, '2nd Sem', 1),
(9, 1, 'CCS06', 'App Development', 3, '1st Sem', 2),
(10, 1, 'PDC10', 'Wordpress', 3, '1st Sem', 3),
(12, 3, 'PSD', 'Photoshop', 3, '1st Sem', 4),
(13, 4, 'ISIS', 'Information Secure In Security', 3, '1st Sem', 1);

-- --------------------------------------------------------

--
-- Table structure for table `enrollees`
--

CREATE TABLE `enrollees` (
  `id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_mobile` varchar(15) NOT NULL,
  `street_address` text NOT NULL,
  `program_applying_for` int(11) NOT NULL,
  `year_level` enum('1','2','3','4') NOT NULL,
  `term` enum('1st Sem','2nd Sem') NOT NULL,
  `uploaded_grade_file` varchar(255) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollees`
--

INSERT INTO `enrollees` (`id`, `last_name`, `first_name`, `middle_name`, `gender`, `email`, `contact_mobile`, `street_address`, `program_applying_for`, `year_level`, `term`, `uploaded_grade_file`, `section_id`, `student_id`, `created_at`) VALUES
(1, 'David', 'Don Henessy', 'Serrano', 'Male', 'don.hdavid@gmail.com', '09918031131', '6043 Jupiter St. Dona Anicia Subd.', 1, '1', '1st Sem', '/uploads/grades/674a27533e4d0_3455956-200.png', 29, '2024-11-001', '2024-11-29 20:42:59'),
(2, 'Carino', 'Marcus Jeremy', 'Mallari', 'Male', 'marcus@gmail.com', '09918031132', 'Barangay Angeles', 2, '1', '1st Sem', '/uploads/grades/674a290f38128_272340.png', 11, '2024-11-001', '2024-11-29 20:50:23'),
(3, 'David', 'Iverson', 'Sison', 'Male', 'iverson@gmail.com', '09918031133', 'Porac Mabalas', 3, '1', '1st Sem', '/uploads/grades/674ab3d1d09fb_272340.png', 28, '2024-00-2024', '2024-11-30 06:42:25'),
(4, 'Bernardo', 'Jeannie Darci', 'Tengco', 'Female', 'jini@gmail.com', '09918031134', 'Savannah ', 1, '2', '1st Sem', '/uploads/grades/674acc92e7e5a_3455956-200.png', 30, '20-2024-004', '2024-11-30 08:28:02'),
(5, 'Lopez', 'Shane', 'Kate', 'Female', 'shane@gmail.com', '09918031135', 'Angeles City', 3, '3', '1st Sem', '/uploads/grades/674b0516efb5e_272340.png', NULL, NULL, '2024-11-30 12:29:10'),
(6, 'Angga', 'Jean Carlo', 'Carlos', 'Male', 'jc@gmail.com', '09918031136', 'Angeles City', 3, '1', '1st Sem', '/uploads/grades/674b10a058403_272340.png', 28, '2024-00-223', '2024-11-30 13:18:24'),
(8, 'Tipon', 'Jovita', 'Lopez', 'Female', 'jovita@gmail.com', '09918031138', 'San Fernando', 3, '4', '1st Sem', '/uploads/grades/674b1cfac2748_272340.png', 33, '2024-00-2031', '2024-11-30 14:11:06'),
(10, 'Naguit', 'Kate Louise', 'Visbal', 'Female', 'kate@gmail.com', '09918031140', 'Arayat Pampanga', 4, '1', '1st Sem', '/uploads/grades/67518dda498b1_Assorted Cup Cakes.png', 1, '2025', '2024-12-05 11:26:18');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `program_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `lastname`, `firstname`, `contact`, `email`, `username`, `password_hash`, `created_at`, `updated_at`, `program_id`) VALUES
(3, 'David', 'Don Henessy', '09918031131', 'don.hdavid@gmail.com', 'don', '$2y$10$k73WUYeZIy/KEq4NaKxuUONB.OpO1ym3iIO3qXrX9alp8wK24cgwi', '2024-11-19 00:06:27', '2024-12-05 22:17:35', 1),
(5, 'Carino', 'Marcus', '09918031131', 'marcus@gmail.com', 'marcus', '$2y$10$gnEbuX/vebK5Z5R5hyKkIeKN9Z6Qv4B0poxvlkCKhZBu0vpmO1VNK', '2024-11-19 00:22:46', '2024-12-03 23:09:54', 1),
(8, 'Doe', 'John', '09918031131', 'jdoe@gmail.com', 'jdoe', '$2y$10$pHN51NZL.JwixGeW5WaRyOZF7VAjk5SrBBazU87qZ74kwHvgWzTFa', '2024-11-19 00:56:19', '2024-12-03 23:10:00', 2),
(9, 'Datu', 'Gabby', '09918031131', 'gabby@gmail.com', 'gabby', '$2y$10$ZiRsUVRzm13K8XYvjqqSeu5ln/cxX2qExSXeeGg9/fEj4saM2r0sa', '2024-11-27 20:36:58', '2024-12-03 23:10:04', 3),
(10, 'David', 'Iverson', '09918031135', 'iverson@gmail.com', 'iverson', '$2y$10$fC/vaWPIhKBbx8ijmua6VOIXdZFG7ylFJ/l4o/jkKdgOLWztREP9m', '2024-12-03 23:14:51', '2024-12-05 21:48:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `program_code` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `years` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `program_code`, `title`, `years`) VALUES
(1, 'BSIT', 'Bachelor of Science in Information Technology', 4),
(2, 'BSCS', 'Bachelor of Science in Computer Science', 4),
(3, 'BMMA', 'Bachelor of Multimedia Arts', 4),
(4, 'BSIS', 'Bachelor of Science in Information Security', 4);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `schedID` int(11) NOT NULL,
  `TIME_FROM` time NOT NULL,
  `TIME_TO` time NOT NULL,
  `sched_day` enum('Monday','Tuesday','Wednesday','Thursday','Friday') NOT NULL,
  `sched_semester` varchar(30) NOT NULL,
  `sched_sy` varchar(30) NOT NULL,
  `sched_room` varchar(30) NOT NULL,
  `section_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`schedID`, `TIME_FROM`, `TIME_TO`, `sched_day`, `sched_semester`, `sched_sy`, `sched_room`, `section_id`, `program_id`, `course_id`) VALUES
(7, '08:00:00', '10:00:00', 'Monday', '1st Sem', '2024-2025', 'IT-403', 1, 1, 2),
(8, '10:00:00', '12:00:00', 'Wednesday', '1st Sem', '2024-2025', 'IT-403', 1, 1, 2),
(9, '10:00:00', '00:00:00', 'Monday', '1st Sem', '2024-2025', 'IT-404', 1, 1, 8),
(10, '08:00:00', '10:00:00', 'Thursday', '1st Sem', '2024-2025', 'IT-404', 1, 1, 8),
(11, '14:00:00', '16:30:00', 'Tuesday', '1st Sem', '2024-2025', 'IT-405', 1, 1, 6),
(12, '10:00:00', '12:00:00', 'Thursday', '1st Sem', '2024-2025', 'IT-405', 1, 1, 6),
(15, '10:00:00', '11:30:00', 'Monday', '1st Sem', '2024-2025', 'Online', 11, 2, 3),
(16, '09:00:00', '00:00:00', 'Thursday', '1st Sem', '2024-2025', 'Online', 11, 2, 3),
(17, '17:30:00', '20:30:00', 'Tuesday', '1st Sem', '2024-2025', 'IT-202 | Online', 28, 3, 4),
(18, '19:00:00', '21:00:00', 'Wednesday', '1st Sem', '2024-2025', 'IT-202 | Online', 28, 3, 4),
(19, '08:00:00', '23:00:00', 'Monday', '1st Sem', '2024-2025', 'IT-103 | Online', 33, 3, 12),
(20, '19:00:00', '21:00:00', 'Thursday', '1st Sem', '2024-2025', 'IT-103 | Online', 33, 3, 12),
(21, '10:00:00', '12:00:00', 'Monday', '1st Sem', '2024-2025', 'IT-103 | Online', 34, 4, 13),
(22, '13:00:00', '15:00:00', 'Wednesday', '1st Sem', '2024-2025', 'IT-103 | Online', 34, 4, 13),
(23, '13:00:00', '15:00:00', 'Monday', '1st Sem', '2024-2025', 'IT-103 ', 30, 1, 9),
(24, '17:00:00', '19:00:00', 'Wednesday', '1st Sem', '2024-2025', 'IT-103 ', 30, 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `year_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `program_id`, `name`, `semester`, `year_level`) VALUES
(1, 1, '1A', '1st Sem', 1),
(11, 2, '1A', '1st Sem', 1),
(28, 3, '1A', '1st Sem', 1),
(29, 1, '1B', '1st Sem', 1),
(30, 1, '2A', '1st Sem', 2),
(31, 1, '3A', '1st Sem', 3),
(32, 1, '4A', '1st Sem', 4),
(33, 3, '4A', '1st Sem', 4),
(34, 4, '1A', '1st Sem', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `enrollees`
--
ALTER TABLE `enrollees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_program_id` (`program_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`schedID`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `enrollees`
--
ALTER TABLE `enrollees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `schedID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `fk_program_id` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

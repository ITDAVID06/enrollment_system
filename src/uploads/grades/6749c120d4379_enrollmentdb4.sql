-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2024 at 12:40 PM
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
-- Database: `enrollmentdb4`
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
(1, 1, 'IAS', 'Info Asu Sec', 3, '1st Sem', 3),
(2, 2, 'IAS', 'Info Asu Sec', 3, '1st Sem', 1);

-- --------------------------------------------------------

--
-- Table structure for table `enrollee`
--

CREATE TABLE `enrollee` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `date_of_birth` date NOT NULL,
  `place_of_birth` varchar(100) NOT NULL,
  `contact_mobile` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `profile_picture_path` varchar(255) NOT NULL,
  `emergency_contact_name` varchar(100) NOT NULL,
  `emergency_contact_mobile` varchar(15) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `zipcode` varchar(10) DEFAULT NULL,
  `elementary_school` varchar(100) NOT NULL,
  `high_school` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `grade_file_path` varchar(255) NOT NULL,
  `student_status` enum('Pending','Approved') NOT NULL DEFAULT 'Pending',
  `section_id` int(11) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `sched_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollee`
--

INSERT INTO `enrollee` (`id`, `student_id`, `lastname`, `firstname`, `middlename`, `gender`, `date_of_birth`, `place_of_birth`, `contact_mobile`, `email`, `profile_picture_path`, `emergency_contact_name`, `emergency_contact_mobile`, `province`, `city`, `barangay`, `street_address`, `zipcode`, `elementary_school`, `high_school`, `course`, `grade_file_path`, `student_status`, `section_id`, `program_id`, `sched_id`) VALUES
(1, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-12', 'asdsd', '09184692806', 'marcusjeremyc@gmail.com', 'uploads/coffee.png', 'Marcus Jeremy Cariño', '09184692806', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '2', 'uploads/tabless.txt', 'Pending', NULL, NULL, NULL),
(2, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-13', 'asdsd', '09184692806', 'mar@gmail.com', 'uploads/id dadi.jpg', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/3yr sales TagaBuy.xlsx', 'Pending', NULL, NULL, NULL),
(3, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-12', 'asdsd', '09184692806', 'marmr@gmail.com', 'uploads/coffee.png', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/Schedule.docx', 'Pending', NULL, NULL, NULL),
(4, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-06', 'asdsd', '09184692806', 'masdasdr@gmail.com', 'uploads/JJ+Angeles_Homepage.png', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/462573266_1568198047172178_6162815873347613327_n.jpg', 'Pending', NULL, NULL, NULL),
(5, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-12', 'asdsd', '09184692806', 'asdasddr@gmail.com', 'uploads/November PCX receipt(2nd).jpg', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/studentprofile.mustache.txt', 'Pending', NULL, NULL, NULL),
(6, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-12', 'asdsd', '09184692806', 'fcghhsddr@gmail.com', 'uploads/image (5).png', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/studentprofile.mustache.txt', 'Pending', NULL, NULL, NULL),
(7, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-03', 'asdsd', '09184692806', 'werdadr@gmail.com', 'uploads/coffee.png', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/sched.txt', 'Pending', NULL, NULL, NULL),
(8, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-07', 'sadasd', '09184692806', 'warft@gmail.com', 'uploads/coffee.png', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/Schedule.docx', 'Pending', NULL, NULL, NULL),
(9, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-05', 'sadasd', '09184692806', 'asdawwqt@gmail.com', 'uploads/image (2).png', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/Schedule.docx', 'Pending', NULL, NULL, NULL),
(10, NULL, 'Carino', 'Marcuss', 'Jeremy', 'Male', '2024-11-07', 'sadasd', '09918031331', 'carinomarcu34@gmail.com', 'uploads/headlight.jpg', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/DFD CCS Enrollment System-Page-2.pdf', 'Pending', NULL, NULL, NULL),
(11, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-07', 'dsad', '09184692806', 'marcsdaewqwqaino21@gmail.com', 'uploads/image (5).png', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '2', 'uploads/Data Flow Diagram (1).pdf', 'Pending', NULL, NULL, NULL),
(12, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-06', 'sadasd', '09184692806', 'marcusjeremycarino21@gmail.com', 'uploads/Color Hunt Palette eeedebe6b9a69391852f3645.png', 'Marcus Jeremy Cariño', '09184692806', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/3yr sales TagaBuy.xlsx', 'Pending', NULL, NULL, NULL),
(14, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-05', 'sadasd', '09184692806', 'marcusje21@gmail.com', 'uploads/TagaBili Logo (2).png', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/sched.txt', 'Pending', NULL, NULL, NULL),
(15, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-10-28', 'sadasd', '09184692806', 'marcusjerem212@gmail.com', 'uploads/draft erd.png', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '2', 'uploads/3yr sales TagaBuy.xlsx', 'Pending', NULL, NULL, NULL),
(16, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-10-29', 'sadasd', '09184692806', 'marcusjertm12@gmail.com', 'uploads/coffee.png', 'Jennifer Carino', '09235345', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '2', 'uploads/sched.txt', 'Pending', NULL, NULL, NULL),
(18, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-10-31', 'sadasd', '09184692806', 'marcusjert23232@gmail.com', 'uploads/462565800_549424477899522_2498927618329566609_n.jpg', 'Marcus Jeremy Cariño', '09184692806', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/studentprofile.mustache.txt', 'Pending', NULL, NULL, NULL),
(19, NULL, 'Cariño', 'Marcus', 'Jeremy', 'Male', '2024-11-07', 'sadasd', '09184692806', 'marcusjer3456@gmail.com', 'uploads/image (6).png', 'Marcus Jeremy Cariño', '09184692806', 'Pampanga', 'Angeles', 'Agapito', '191', '2009', 'Clemendes', 'Angeles City Science High School', '1', 'uploads/Schedule.docx', 'Pending', NULL, NULL, NULL);

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
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'BSIT', 'Information Tech', 4),
(2, 'BSCS', 'Bachelor of Science in Computer Science', 4);

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `status` enum('new','old') NOT NULL,
  `previous_school` varchar(255) NOT NULL,
  `program_id` int(11) NOT NULL,
  `year_level` enum('1st-year','2nd-year','3rd-year','4th-year') NOT NULL,
  `term` enum('1st-semester','2nd-semester','summer') NOT NULL,
  `school_year` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `status`, `previous_school`, `program_id`, `year_level`, `term`, `school_year`, `created_at`) VALUES
(1, 'new', 'CLEMENDES', 1, '1st-year', '1st-semester', '2024-2025', '2024-11-29 10:04:50');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `complete_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `complete_name`, `email`, `password_hash`, `created_at`, `updated_at`) VALUES
(1, 'Marcus Jeremy Carino', 'carinomarcus85@gmail.com', '$2y$10$f5ktLbGhhBU288h/4q2rFeta0uz3ws1crAlwKBHIODqL7vdJfvYCO', '2024-11-28 21:38:46', NULL);

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
-- Indexes for table `enrollee`
--
ALTER TABLE `enrollee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `enrollee`
--
ALTER TABLE `enrollee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollee`
--
ALTER TABLE `enrollee`
  ADD CONSTRAINT `enrollee_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `enrollee_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `registration`
--
ALTER TABLE `registration`
  ADD CONSTRAINT `registration_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2024 at 08:12 AM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enrollee`
--
ALTER TABLE `enrollee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `program_id` (`program_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enrollee`
--
ALTER TABLE `enrollee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `enrollee`
--
ALTER TABLE `enrollee`
  ADD CONSTRAINT `enrollee_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `enrollee_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

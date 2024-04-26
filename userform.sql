-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2024 at 05:58 AM
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
-- Database: `userform`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctor_table`
--

CREATE TABLE `doctor_table` (
                                `doc_id` int(11) NOT NULL,
                                `name` varchar(255) NOT NULL,
                                `email` varchar(255) NOT NULL,
                                `password` varchar(255) NOT NULL,
                                `biometric` mediumblob NOT NULL,
                                `code` mediumint(50) NOT NULL,
                                `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_upload`
--

CREATE TABLE `file_upload` (
                               `id` int(11) NOT NULL,
                               `filename` varchar(50) NOT NULL,
                               `folder_path` varchar(100) NOT NULL,
                               `time_stamp` datetime NOT NULL,
                               `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_table`
--

CREATE TABLE `patient_table` (
                                 `patient_id` int(11) NOT NULL,
                                 `name` varchar(255) NOT NULL,
                                 `email` varchar(255) NOT NULL,
                                 `password` varchar(255) NOT NULL,
                                 `code` mediumint(50) NOT NULL,
                                 `status` text NOT NULL,
                                 `biometric` mediumblob NOT NULL,
                                 `doc_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctor_table`
--
ALTER TABLE `doctor_table`
    ADD PRIMARY KEY (`doc_id`);

--
-- Indexes for table `file_upload`
--
ALTER TABLE `file_upload`
    ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `patient_table`
--
ALTER TABLE `patient_table`
    ADD PRIMARY KEY (`patient_id`),
  ADD KEY `doc_id` (`doc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctor_table`
--
ALTER TABLE `doctor_table`
    MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_upload`
--
ALTER TABLE `file_upload`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_table`
--
ALTER TABLE `patient_table`
    MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `file_upload`
--
ALTER TABLE `file_upload`
    ADD CONSTRAINT `file_upload_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_table` (`patient_id`);

--
-- Constraints for table `patient_table`
--
ALTER TABLE `patient_table`
    ADD CONSTRAINT `patient_table_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doctor_table` (`doc_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

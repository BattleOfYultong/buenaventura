-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2024 at 11:30 AM
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
-- Database: `apar`
--

-- --------------------------------------------------------

--
-- Table structure for table `admintbl`
--

CREATE TABLE `admintbl` (
  `adminID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `CustomerName` varchar(100) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `InvoiceID` int(11) NOT NULL,
  `VendorID` int(11) DEFAULT NULL,
  `InvoiceNumber` varchar(50) DEFAULT NULL,
  `InvoiceDate` date DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `AmountDue` decimal(10,2) DEFAULT NULL,
  `PaymentStatus` varchar(20) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`InvoiceID`, `VendorID`, `InvoiceNumber`, `InvoiceDate`, `DueDate`, `AmountDue`, `PaymentStatus`, `userID`) VALUES
(3, 0, 'INV_665e6662989ab', '2024-06-25', '2024-06-21', 123.00, 'pending', 9),
(8, NULL, 'INV_665e7b4b26cef', '2024-05-29', '2024-06-17', 20900.00, 'due', 9),
(10, NULL, 'INV_665e7ba87676e', '2024-05-30', '2024-06-17', 102032.00, 'pending', 14);

-- --------------------------------------------------------

--
-- Table structure for table `logintbl`
--

CREATE TABLE `logintbl` (
  `userID` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `account_type` int(11) DEFAULT NULL,
  `profile_pic_path` varchar(255) DEFAULT NULL,
  `acc_creation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logintbl`
--

INSERT INTO `logintbl` (`userID`, `email`, `password`, `profile_pic`, `account_type`, `profile_pic_path`, `acc_creation_date`) VALUES
(4, 'Admin@gmail.com', 'admin', NULL, 1, 'd132e625-af75-4fc8-b685-abd9f7c88429.jpg', '2024-06-03'),
(9, 'Johns@gmail.com', '1233', NULL, NULL, '665ddae89718a.jpg', NULL),
(14, 'anonymouse@gmail.com', '123', NULL, NULL, '665e7b981ba5d.jpg', NULL),
(15, 'put@gmail.com', '123', NULL, NULL, '665e7cecc6eb3.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trip`
--

CREATE TABLE `trip` (
  `TripID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `TripDate` date DEFAULT NULL,
  `TripFare` decimal(10,2) DEFAULT NULL,
  `PaymentStatus` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `VendorID` int(11) NOT NULL,
  `VendorName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admintbl`
--
ALTER TABLE `admintbl`
  ADD PRIMARY KEY (`adminID`),
  ADD UNIQUE KEY `userID` (`userID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `userID` (`userID`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`InvoiceID`),
  ADD KEY `VendorID` (`VendorID`),
  ADD KEY `fk_userID` (`userID`);

--
-- Indexes for table `logintbl`
--
ALTER TABLE `logintbl`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `trip`
--
ALTER TABLE `trip`
  ADD PRIMARY KEY (`TripID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`VendorID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admintbl`
--
ALTER TABLE `admintbl`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `InvoiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `logintbl`
--
ALTER TABLE `logintbl`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `trip`
--
ALTER TABLE `trip`
  MODIFY `TripID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `VendorID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admintbl`
--
ALTER TABLE `admintbl`
  ADD CONSTRAINT `admintbl_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `logintbl` (`userID`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `logintbl` (`userID`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_userID` FOREIGN KEY (`userID`) REFERENCES `logintbl` (`userID`),
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`VendorID`) REFERENCES `vendor` (`VendorID`);

--
-- Constraints for table `trip`
--
ALTER TABLE `trip`
  ADD CONSTRAINT `trip_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2021 at 10:00 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wms`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE `adminlogin` (
  `Id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL DEFAULT 'admin',
  `password` varchar(255) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `adminlogin`
--

INSERT INTO `adminlogin` (`Id`, `username`, `password`) VALUES
(1, 'admin', 'admintest'),
(2, 'pradip', 'pradip');

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin_tbl`
--

CREATE TABLE `adminlogin_tbl` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `code` mediumint(50) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `adminlogin_tbl`
--

INSERT INTO `adminlogin_tbl` (`id`, `name`, `email`, `password`, `code`, `status`) VALUES
(2, 'pradip', 'pradippatil87665@gmail.com', 'pradip', 0, 'verified');

-- --------------------------------------------------------

--

-- --------------------------------------------------------


--
-- Table structure for table `usertable`
--

CREATE TABLE `usertable` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `contactno` decimal(10) not NULL,
  `email` varchar(255) NOT NULL,
  `district` varchar(50),
  `taluka` varchar(50),
  `city` varchar(50),
  `password` varchar(255) NOT NULL,
  `code` mediumint(50) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Table structure for table `center`
--

CREATE TABLE `center` (
  `centerid` int(11) PRIMARY KEY AUTO_INCREMENT,
  `centername` varchar(255) NOT NULL,
  `contactno` decimal(10) not NULL,
  `centeremail` varchar(255) NOT NULL,
  `district` varchar(50),
  `taluka` varchar(50),
  `city` varchar(50),
  `password` varchar(255) NOT NULL,
  `code` mediumint(50) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `garbageinfo`
--
CREATE TABLE `garbageinfo` (
  `GarbageId` int(11) primary key AUTO_INCREMENT,
  `id` int(11) references usertable (id),
  `centerid` int(11) references center(centerid),
  `name` varchar(255) NOT NULL,
  `wastetype` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `locationdescription` varchar(255) NOT NULL,
  `file` blob NOT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `wastscheduletbl` (
  `id` int(11) primary key ,
  `centerid`int(11) references center(centerid),
  `pickupdate` date ,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `feedback` (
  `feedbackid` int(11) primary key AUTO_INCREMENT,
  `id` int(11) references usertable (id),
  `feedbackdate` date ,
  `feedbacktext` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

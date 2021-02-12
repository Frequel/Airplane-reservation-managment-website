-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2019 at 06:37 PM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s257563`
--

-- --------------------------------------------------------

--
-- Table structure for table `dimension`
--

CREATE TABLE `dimension` (
  `r` int(11) NOT NULL,
  `c` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dimension`
--

INSERT INTO `dimension` (`r`, `c`) VALUES
(10, 6);

-- --------------------------------------------------------

--
-- Table structure for table `seat`
--

CREATE TABLE `seat` (
  `pos` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `status` int(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `seat`
--

INSERT INTO `seat` (`pos`, `username`, `status`) VALUES
(8, 'u2@p.it', 2),
(14, 'u2@p.it', 2),
(19, 'u1@p.it', 1),
(20, 'u2@p.it', 2),
(22, 'u1@p.it', 1),
(24, 'u2@p.it', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`) VALUES
('u1@p.it', '$2y$10$qigcfZZQKqxqTF6s2P51POJLsM.Dx5hWb4AZgClObwhdnhqYHWihu'),
('u2@p.it', '$2y$10$AEOwzG.eqb9AIFxWY7niIOoqTNM40p9CSSS4GXxsk67Lyq3Pfekp.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dimension`
--
ALTER TABLE `dimension`
  ADD PRIMARY KEY (`c`);

--
-- Indexes for table `seat`
--
ALTER TABLE `seat`
  ADD PRIMARY KEY (`pos`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

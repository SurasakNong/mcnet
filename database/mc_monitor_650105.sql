-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2022 at 06:33 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mc_monitor`
--

-- --------------------------------------------------------

--
-- Table structure for table `bd`
--

CREATE TABLE `bd` (
  `bd_id` tinyint(2) NOT NULL,
  `bd_name` varchar(50) NOT NULL,
  `bd_mc` int(11) NOT NULL,
  `bd_rpm` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bd`
--

INSERT INTO `bd` (`bd_id`, `bd_name`, `bd_mc`, `bd_rpm`) VALUES
(1, 'อาคาร 1', 144, 18.5),
(2, 'อาคาร 2', 222, 19.5),
(3, 'อาคาร 3', 189, 18),
(4, 'อาคาร 5', 97, 20),
(5, 'อาคาร 12', 130, 17);

-- --------------------------------------------------------

--
-- Table structure for table `depart`
--

CREATE TABLE `depart` (
  `id_depart` tinyint(3) NOT NULL,
  `depart` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `depart`
--

INSERT INTO `depart` (`id_depart`, `depart`) VALUES
(2, 'สต๊อกวัตถุดิบ2'),
(3, 'เส้นใย'),
(4, 'ตีด้าย อ.4'),
(5, 'ตีด้าย อ.7'),
(6, 'ทออาคาร 1'),
(7, 'ทออาคาร 2'),
(8, 'ทออาคาร 3'),
(9, 'ทออาคาร 5'),
(10, 'ทออาคาร 12'),
(11, 'สต๊อกวัตถุดิบ12'),
(12, 'ส่วนทอ1'),
(13, 'ส่วนทอ2'),
(14, 'เตรียมฟอก'),
(15, 'ฟอกย้อม'),
(16, 'อบ'),
(17, 'สำเร็จรูป'),
(18, 'แพ็คกิ้ง'),
(19, 'คลังสินค้า'),
(20, 'การตลาด'),
(21, 'บัญชี/การเงิน'),
(22, 'บุคคลและธุรการ'),
(23, 'วารินฯ'),
(24, 'สโตร์กลาง'),
(25, 'จัดซื้อ'),
(26, 'สาธารณูปโภคฯ'),
(27, 'VIP1'),
(28, 'VIP2');

-- --------------------------------------------------------

--
-- Table structure for table `group_mc`
--

CREATE TABLE `group_mc` (
  `group_id` tinyint(4) NOT NULL,
  `bd_id` tinyint(4) NOT NULL,
  `group_name` varchar(50) NOT NULL,
  `group_mc` int(11) NOT NULL,
  `group_rpm` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `group_mc`
--

INSERT INTO `group_mc` (`group_id`, `bd_id`, `group_name`, `group_mc`, `group_rpm`) VALUES
(1, 1, '1A', 36, 19),
(2, 1, '1B', 36, 18),
(3, 1, '1C', 36, 19),
(4, 1, '1D', 0, 0),
(5, 1, '1E', 0, 0),
(6, 1, '1F', 36, 18.5),
(7, 2, '2A', 66, 20),
(8, 2, '2B', 36, 18),
(9, 2, '2C', 48, 18),
(10, 2, '2D', 36, 18),
(11, 2, '2E', 36, 18),
(12, 3, '3A', 36, 18),
(13, 3, '3B', 36, 18),
(14, 3, '3C', 36, 18.5),
(15, 3, '3D', 36, 19),
(16, 3, '3E', 45, 19),
(17, 4, '5A', 36, 20),
(18, 4, '5B', 24, 19),
(19, 4, '5C', 37, 19);

-- --------------------------------------------------------

--
-- Table structure for table `mc`
--

CREATE TABLE `mc` (
  `id_mc` int(11) NOT NULL,
  `mc` varchar(6) NOT NULL,
  `group_id` tinyint(4) NOT NULL,
  `shift_id` tinyint(4) NOT NULL,
  `mc_rpm` float NOT NULL,
  `mc_used` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mc`
--

INSERT INTO `mc` (`id_mc`, `mc`, `group_id`, `shift_id`, `mc_rpm`, `mc_used`) VALUES
(1, '5C19', 19, 1, 18.52, 1),
(2, '5C20', 19, 1, 18.5, 1),
(3, '5C21', 19, 1, 19, 1),
(4, '5C22', 19, 1, 19, 1),
(5, '5C23', 19, 1, 19, 1),
(6, '5C24', 19, 1, 18.5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `meter_mc`
--

CREATE TABLE `meter_mc` (
  `meter_date` date NOT NULL,
  `shift_no` tinyint(4) NOT NULL,
  `mc` varchar(6) NOT NULL,
  `meter` int(11) NOT NULL,
  `rpm` int(11) NOT NULL,
  `on_t` int(11) NOT NULL,
  `top` int(11) NOT NULL,
  `top_t` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `mid_t` int(11) NOT NULL,
  `epa` int(11) NOT NULL,
  `epa_t` int(11) NOT NULL,
  `bob` int(11) NOT NULL DEFAULT 0,
  `bob_t` int(11) NOT NULL DEFAULT 0,
  `off` int(11) NOT NULL,
  `off_t` int(11) NOT NULL,
  `tmp` float NOT NULL,
  `hmd` float NOT NULL,
  `mc_rpm` float NOT NULL,
  `id_mc` int(11) NOT NULL,
  `shift_min` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `meter_mc`
--

INSERT INTO `meter_mc` (`meter_date`, `shift_no`, `mc`, `meter`, `rpm`, `on_t`, `top`, `top_t`, `mid`, `mid_t`, `epa`, `epa_t`, `bob`, `bob_t`, `off`, `off_t`, `tmp`, `hmd`, `mc_rpm`, `id_mc`, `shift_min`) VALUES
('2021-10-09', 1, '5C22', 1564, 25, 79, 0, 0, 20, 83, 0, 0, 5, 15, 16, 22, 43.69, 47.29, 19, 4, 720),
('2021-10-09', 1, '5C23', 1726, 0, 88, 0, 0, 10, 55, 1, 0, 4, 937, 37, 36, 43.67, 47.31, 19, 5, 720),
('2021-10-09', 1, '5C24', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 14055, 43.67, 47.05, 18.5, 6, 720),
('2021-10-09', 1, '5C19', 3955, 0, 189, 0, 0, 5, 211, 3, 7, 0, 0, 5, 10, 43.61, 52.09, 18.52, 1, 720),
('2021-10-09', 1, '5C20', 587, 7, 39, 0, 0, 15, 52, 1, 0, 4, 0, 100, 58, 43.63, 51.48, 18.5, 2, 720),
('2021-10-09', 1, '5C21', 1699, 0, 118, 0, 0, 18, 53, 6, 1, 1, 0, 120, 30, 43.67, 51.34, 19, 3, 720),
('2021-10-09', 2, '5C19', 4138, 22, 198, 0, 0, 6, 24, 3, 7, 0, 0, 7, 12, 43.2, 52.22, 18.52, 1, 720),
('2021-10-09', 2, '5C20', 707, 7, 47, 0, 0, 15, 52, 1, 0, 4, 0, 114, 61, 43.18, 52.33, 18.5, 2, 720),
('2021-10-09', 2, '5C21', 1826, 21, 127, 0, 0, 18, 53, 6, 1, 1, 0, 130, 33, 43.14, 52.4, 19, 3, 720),
('2021-10-09', 2, '5C22', 1703, 23, 86, 0, 0, 24, 87, 0, 0, 5, 15, 18, 23, 42.16, 47.67, 19, 4, 720),
('2021-10-09', 2, '5C23', 1726, 0, 88, 0, 0, 10, 55, 1, 0, 4, 1658, 37, 36, 42.25, 47.81, 19, 5, 720),
('2021-10-09', 2, '5C24', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 14776, 42.22, 47.53, 18.5, 6, 720),
('2021-10-08', 1, '5C22', 3071, 0, 155, 0, 0, 40, 170, 1, 2, 6, 16, 31, 30, 42.36, 53.26, 19, 4, 720),
('2021-10-08', 1, '5C23', 2915, 22, 151, 0, 0, 12, 64, 1, 0, 3, 14, 88, 88, 42.34, 51.54, 19, 5, 720),
('2021-10-08', 1, '5C24', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 22518, 42.39, 51.73, 18.5, 6, 720),
('2021-10-08', 1, '5C19', 5916, 21, 292, 0, 0, 10, 49, 4, 7, 0, 0, 13, 19, 41.23, 57.33, 18.52, 1, 720),
('2021-10-08', 1, '5C20', 1171, 15, 78, 0, 0, 33, 117, 1, 0, 4, 0, 190, 90, 41.34, 57.43, 18.5, 2, 720),
('2021-10-08', 1, '5C21', 2815, 7, 194, 0, 0, 24, 75, 6, 1, 2, 0, 200, 66, 41.31, 57.38, 19, 3, 720),
('2021-10-11', 1, '5C22', 8530, 22, 414, 0, 0, 16, 18, 6, 8, 5, 4, 109, 61, 34.87, 81.27, 19, 4, 720),
('2021-10-11', 1, '5C23', 3386, 0, 233, 0, 0, 66, 136, 5, 5, 8, 1, 455, 126, 34.76, 81.54, 19, 5, 720),
('2021-10-11', 1, '5C24', 3880, 0, 273, 0, 0, 18, 39, 13, 23, 7, 70, 272, 95, 34.84, 81, 18.5, 6, 720),
('2021-10-11', 1, '5C19', 8105, 23, 416, 0, 0, 21, 41, 7, 17, 2, 0, 55, 37, 33.64, 81.01, 18.52, 1, 720),
('2021-10-11', 1, '5C20', 4155, 0, 218, 0, 0, 18, 13, 0, 0, 4, 0, 178, 217, 33.75, 80.81, 18.5, 2, 720),
('2021-10-11', 1, '5C21', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 521, 33.72, 80.37, 19, 3, 720),
('2021-10-11', 2, '5C19', 784, 0, 37, 0, 0, 49, 151, 11, 30, 21, 16, 45, 157, 34.57, 73.34, 18.52, 1, 720),
('2021-10-11', 2, '5C20', 985, 0, 46, 0, 0, 32, 77, 1, 1, 3, 2, 61, 318, 34.5, 72.96, 18.5, 2, 720),
('2021-10-11', 2, '5C21', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 720, 34.55, 72.89, 19, 3, 720),
('2021-10-11', 2, '5C22', 1320, 23, 59, 0, 0, 11, 33, 12, 30, 8, 6, 90, 494, 32.96, 83.75, 19, 4, 720),
('2021-10-11', 2, '5C23', 221, 0, 14, 0, 0, 87, 272, 3, 2, 18, 13, 51, 102, 32.93, 83.51, 19, 5, 720),
('2021-10-11', 2, '5C24', 498, 0, 31, 0, 0, 40, 110, 8, 23, 13, 28, 99, 275, 32.91, 83.74, 18.5, 6, 720),
('2021-10-12', 1, '5C22', 10513, 22, 507, 0, 0, 18, 11, 7, 10, 1, 0, 32, 154, 35.27, 80.68, 19, 4, 720),
('2021-10-12', 1, '5C23', 5447, 0, 377, 0, 0, 87, 142, 4, 1, 6, 1, 700, 179, 35.18, 80.89, 19, 5, 720),
('2021-10-12', 1, '5C24', 6660, 0, 470, 0, 0, 42, 70, 10, 12, 1, 0, 494, 124, 35.31, 80.45, 18.5, 6, 720),
('2021-10-12', 1, '5C19', 10084, 0, 521, 0, 0, 54, 93, 12, 12, 2, 1, 74, 81, 36.42, 71.46, 18.52, 1, 720),
('2021-10-12', 1, '5C20', 6754, 22, 342, 0, 0, 20, 32, 0, 0, 1, 0, 120, 329, 36.42, 71.89, 18.5, 2, 720),
('2021-10-12', 1, '5C21', 2557, 0, 114, 0, 0, 13, 11, 5, 7, 0, 0, 19, 581, 36.37, 71.03, 19, 3, 720),
('2021-10-12', 2, '5C19', 999, 23, 48, 0, 0, 56, 193, 4, 19, 22, 32, 66, 192, 35.26, 64.98, 18.52, 1, 720),
('2021-10-12', 2, '5C20', 1079, 0, 53, 0, 0, 26, 60, 2, 1, 2, 1, 63, 459, 35.24, 64.94, 18.5, 2, 720),
('2021-10-12', 2, '5C21', 1124, 23, 47, 0, 0, 36, 111, 2, 2, 4, 2, 71, 337, 35.24, 64.44, 19, 3, 720),
('2021-10-12', 2, '5C22', 1212, 0, 53, 0, 0, 26, 88, 5, 3, 0, 0, 107, 417, 32.82, 75.63, 19, 4, 720),
('2021-10-12', 2, '5C23', 314, 0, 20, 0, 0, 80, 243, 2, 4, 13, 14, 60, 97, 32.89, 75.58, 19, 5, 720),
('2021-10-12', 2, '5C24', 59, 0, 1, 0, 0, 40, 113, 12, 22, 4, 62, 39, 251, 32.95, 76.14, 18.5, 6, 720),
('2021-10-13', 1, '5C19', 9768, 0, 502, 0, 0, 55, 78, 13, 16, 0, 0, 72, 108, 36.65, 63.07, 18.52, 1, 720),
('2021-10-13', 1, '5C20', 8967, 0, 462, 0, 0, 18, 30, 0, 0, 0, 0, 90, 209, 36.72, 63.14, 18.5, 2, 720),
('2021-10-13', 1, '5C21', 10482, 0, 462, 0, 0, 44, 77, 20, 30, 2, 0, 61, 114, 36.69, 64.13, 19, 3, 720),
('2021-10-13', 1, '5C22', 11627, 0, 559, 0, 0, 15, 9, 5, 10, 6, 6, 38, 81, 34.87, 73.06, 19, 4, 720),
('2021-10-13', 1, '5C23', 5339, 0, 371, 0, 0, 68, 155, 1, 1, 5, 0, 687, 178, 34.8, 73.32, 19, 5, 720),
('2021-10-13', 1, '5C24', 5504, 0, 382, 0, 0, 25, 37, 3, 2, 25, 22, 422, 232, 34.78, 73.75, 18.5, 6, 720),
('2021-10-13', 2, '5C22', 1225, 0, 54, 0, 0, 13, 35, 7, 9, 10, 5, 102, 442, 32.89, 84.24, 19, 4, 720),
('2021-10-13', 2, '5C23', 244, 0, 15, 0, 0, 95, 278, 5, 4, 9, 4, 45, 112, 32.85, 83.74, 19, 5, 720),
('2021-10-13', 2, '5C24', 436, 0, 29, 0, 0, 51, 122, 14, 74, 6, 3, 62, 243, 32.96, 84.05, 18.5, 6, 720),
('2021-10-13', 2, '5C19', 603, 0, 27, 0, 0, 51, 212, 5, 7, 23, 52, 56, 236, 32.92, 78.69, 18.52, 1, 720),
('2021-10-13', 2, '5C20', 669, 0, 29, 0, 0, 27, 66, 4, 3, 6, 4, 77, 522, 32.79, 79.23, 18.5, 2, 720),
('2021-10-13', 2, '5C21', 445, 22, 16, 0, 0, 36, 136, 7, 18, 5, 3, 91, 295, 32.88, 79.04, 19, 3, 720);

-- --------------------------------------------------------

--
-- Table structure for table `monitor_mc`
--

CREATE TABLE `monitor_mc` (
  `mc` varchar(6) NOT NULL,
  `mc_status` tinyint(3) NOT NULL DEFAULT 0,
  `meter` int(11) NOT NULL DEFAULT 0,
  `rpm` float NOT NULL DEFAULT 0,
  `on_t` int(11) NOT NULL DEFAULT 0,
  `top` int(11) NOT NULL DEFAULT 0,
  `top_t` int(11) NOT NULL DEFAULT 0,
  `mid` int(11) NOT NULL DEFAULT 0,
  `mid_t` int(11) NOT NULL DEFAULT 0,
  `epa` int(11) NOT NULL DEFAULT 0,
  `epa_t` int(11) NOT NULL DEFAULT 0,
  `bob` int(11) NOT NULL DEFAULT 0,
  `bob_t` int(11) NOT NULL DEFAULT 0,
  `off` int(11) NOT NULL DEFAULT 0,
  `off_t` int(11) NOT NULL DEFAULT 0,
  `tmp` float NOT NULL DEFAULT 0,
  `hmd` float NOT NULL DEFAULT 0,
  `meter_date` date NOT NULL DEFAULT current_timestamp(),
  `shift_no` tinyint(4) NOT NULL,
  `shift_be` time NOT NULL,
  `shift_end` time NOT NULL,
  `shift_min` int(11) NOT NULL,
  `event_stamp` datetime NOT NULL DEFAULT current_timestamp(),
  `comm` tinyint(11) NOT NULL DEFAULT 0,
  `strComm` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `monitor_mc`
--

INSERT INTO `monitor_mc` (`mc`, `mc_status`, `meter`, `rpm`, `on_t`, `top`, `top_t`, `mid`, `mid_t`, `epa`, `epa_t`, `bob`, `bob_t`, `off`, `off_t`, `tmp`, `hmd`, `meter_date`, `shift_no`, `shift_be`, `shift_end`, `shift_min`, `event_stamp`, `comm`, `strComm`) VALUES
('5C19', 2, 6854, 0, 208645, 0, 0, 50, 66326, 13, 16762, 8, 1835, 70, 31727, 36.19, 67.28, '2021-10-14', 1, '08:00:00', '20:00:00', 720, '2021-10-14 17:12:06', 0, ''),
('5C20', 5, 5304, 0, 161415, 0, 0, 24, 14577, 1, 37, 0, 0, 59, 151058, 36.1, 66.93, '2021-10-14', 1, '08:00:00', '20:00:00', 720, '2021-10-14 17:19:53', 0, ''),
('5C21', 3, 8626, 0, 228322, 0, 0, 44, 49714, 9, 11351, 3, 105, 36, 20039, 36.1, 66.96, '2021-10-14', 1, '08:00:00', '20:00:00', 720, '2021-10-14 17:10:16', 0, ''),
('5C22', 2, 8420, 0, 241645, 0, 0, 36, 45130, 9, 8180, 0, 0, 23, 24622, 34.51, 76.18, '2021-10-14', 1, '08:00:00', '20:00:00', 720, '2021-10-14 17:07:26', 0, ''),
('5C23', 2, 3344, 0, 137849, 0, 0, 86, 111557, 9, 4672, 4, 314, 414, 65212, 34.69, 77.15, '2021-10-14', 1, '08:00:00', '20:00:00', 720, '2021-10-14 17:14:45', 0, ''),
('5C24', 2, 4003, 0, 158818, 0, 0, 35, 52819, 41, 37876, 1, 32, 335, 57605, 34.6, 75.99, '2021-10-14', 1, '08:00:00', '20:00:00', 720, '2021-10-14 17:07:26', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE `shift` (
  `shift_id` tinyint(2) NOT NULL,
  `shift_name` varchar(50) NOT NULL,
  `shift_count` tinyint(4) NOT NULL,
  `shift_be1` time DEFAULT NULL,
  `shift_en1` time DEFAULT NULL,
  `shift_be2` time DEFAULT NULL,
  `shift_en2` time DEFAULT NULL,
  `shift_be3` time DEFAULT NULL,
  `shift_en3` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shift`
--

INSERT INTO `shift` (`shift_id`, `shift_name`, `shift_count`, `shift_be1`, `shift_en1`, `shift_be2`, `shift_en2`, `shift_be3`, `shift_en3`) VALUES
(1, '2กะ(8 ot4 +8 ot4)', 2, '08:00:00', '20:00:00', '20:00:00', '08:00:00', NULL, NULL),
(2, '3กะ', 3, '07:00:00', '15:00:00', '15:00:00', '23:00:00', '23:00:00', '07:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `shift_set`
--

CREATE TABLE `shift_set` (
  `shift_set_id` tinyint(4) NOT NULL,
  `shift_id` tinyint(4) NOT NULL,
  `shift_no` tinyint(4) NOT NULL,
  `shift_be` time NOT NULL,
  `shift_en` time NOT NULL,
  `shift_min` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shift_set`
--

INSERT INTO `shift_set` (`shift_set_id`, `shift_id`, `shift_no`, `shift_be`, `shift_en`, `shift_min`) VALUES
(1, 1, 1, '08:00:00', '20:00:00', 720),
(2, 1, 2, '20:00:00', '08:00:00', 720);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `id_depart` tinyint(3) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(2048) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `id_depart`, `username`, `password`, `type`, `created`, `modified`) VALUES
(12, 'สุรศักดิ์', 'เอี่ยมเสริม', 5, 'admin', '$2y$10$UGr/G8YlrfTteegOMWogh.3MVf.EiXgyVa3..YLt9Xj6l60jRnO.W', 2, '2021-08-21 13:08:23', '2021-09-10 05:55:42'),
(13, 'surasak', 'iamserm', 2, 'aaaaa', '$2y$10$nFiCR0v7hW1DnTRzj8GjVOcnyPrZf/IoJuy0a6bP3r.6oWL2oCfgW', 0, '2021-08-24 08:39:48', '2021-08-28 03:29:40'),
(17, 'ddddd', 'ddddd', 4, 'ddddd', '$2y$10$CBJ.k5kKTsZfBzv7HIWx3evoA9unEOPb5Bjtzy7yeAM/vqu/gdiEa', 2, '2021-08-24 13:56:47', '2021-08-28 03:29:46'),
(18, 'ccccc', 'ccccc', 5, 'cccccc', '$2y$10$HfzI6gvBLge6ufecIm7c9uUKeHofvEdtupbh7TbSo0WyxssUkDxKK', 1, '2021-08-24 13:59:51', '2021-08-28 03:29:48'),
(19, 'eeeee', 'eeeee', 6, 'eeeee', '$2y$10$xY3EyRyWRrESJ7zln1deTeRE8qP6xjMU8srgSphDAmsLB1dJnUPX6', 0, '2021-08-24 14:00:58', '2021-08-28 03:29:53'),
(22, 'fffff', 'fffff', 7, 'fffff', '$2y$10$Xv416BQk0TyxlyUH2jvwd.u42448JZ1pl6BCgwjxhP/vNhUZytSTq', 1, '2021-08-25 08:43:30', '2021-09-18 07:55:36'),
(23, 'ผู้ใช้งาน', 'ทดสอบ', 27, 'user', '$2y$10$LKGBIoJPK1BCYvLjEG/Tv.SZbfVOYVp1a.ML8fXtK9e4lqZNszuRe', 0, '2021-09-08 17:04:01', '2021-09-20 07:53:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bd`
--
ALTER TABLE `bd`
  ADD PRIMARY KEY (`bd_id`);

--
-- Indexes for table `depart`
--
ALTER TABLE `depart`
  ADD PRIMARY KEY (`id_depart`);

--
-- Indexes for table `group_mc`
--
ALTER TABLE `group_mc`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `mc`
--
ALTER TABLE `mc`
  ADD PRIMARY KEY (`id_mc`);

--
-- Indexes for table `monitor_mc`
--
ALTER TABLE `monitor_mc`
  ADD PRIMARY KEY (`mc`);

--
-- Indexes for table `shift`
--
ALTER TABLE `shift`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `shift_set`
--
ALTER TABLE `shift_set`
  ADD PRIMARY KEY (`shift_set_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bd`
--
ALTER TABLE `bd`
  MODIFY `bd_id` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `depart`
--
ALTER TABLE `depart`
  MODIFY `id_depart` tinyint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `group_mc`
--
ALTER TABLE `group_mc`
  MODIFY `group_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `mc`
--
ALTER TABLE `mc`
  MODIFY `id_mc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `shift`
--
ALTER TABLE `shift`
  MODIFY `shift_id` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shift_set`
--
ALTER TABLE `shift_set`
  MODIFY `shift_set_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

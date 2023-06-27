-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jul 31, 2013 at 08:57 AM
-- Server version: 5.5.29
-- PHP Version: 5.4.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `smi_old`
--

-- --------------------------------------------------------

--
-- Table structure for table `matreg`
--

CREATE TABLE IF NOT EXISTS `matreg` (
  `Material` varchar(4) DEFAULT NULL,
  `RegisterGroup` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `matreg`
--

INSERT INTO `matreg` (`Material`, `RegisterGroup`) VALUES
('0000', ''),
('A143', '400_1'),
('A241', '400_1'),
('A243', '400_1'),
('A244', '400_1'),
('B141', '500_1'),
('B143', '500_1'),
('B163', '500_1'),
('B240', '500_1'),
('B241', '500_1'),
('B243', '500_1'),
('B244', '500_1'),
('B245', '500_1'),
('B246', '500_1'),
('B247', '500_1'),
('B248', '500_1'),
('B249', '500_1'),
('B260', '500_1'),
('B263', '500_1'),
('B264', '500_1'),
('B265', '500_1'),
('B266', '500_1'),
('B267', '500_1'),
('B269', '500_1'),
('C449', '440_100'),
('C460', '440_180'),
('C463', '440_40'),
('C464', '440_40'),
('C464', '540_40'),
('C465', '440_60'),
('C466', '440_80'),
('C466', '540_60'),
('C466', '540_80'),
('C467', '440_160'),
('C467', '540_120'),
('C467', '540_160'),
('C469', '440_200'),
('C469', '540_200'),
('C960', '545_60'),
('C967', '545_60'),
('C969', '545_200'),
('D111', '400_1'),
('D112', '400_1'),
('D114', '400_1'),
('D116', '400_1'),
('D212', '400_1'),
('D212', '500_1'),
('D214', '400_1'),
('D311', '440_0.5'),
('D312', '440_1'),
('D314', '440_1'),
('D316', '440_2'),
('D324', '440_2'),
('D412', '440_1'),
('D412', '540_1'),
('D422', '540_2'),
('D714', '440_1'),
('D715', '440_2'),
('D814', '004_1'),
('D816', '004_1'),
('E240', '500_1'),
('E245', '500_1'),
('E260', '500_1'),
('E263', '500_1'),
('E265', '500_1'),
('F311', '545_1'),
('F312', '545_1'),
('F314', '545_1'),
('F315', '545_1'),
('F411', '545_1'),
('F412', '545_1'),
('G460', '540_180'),
('G465', '540_60'),
('G960', '545_60'),
('G965', '545_60'),
('H041', '650_1'),
('H050', '000_0'),
('H060', '000_0'),
('H069', '000_0'),
('H104', '400_1'),
('H105', '500_1'),
('H108', '004_1'),
('H109', '004_1'),
('H113', '400_1'),
('H116', '440_1'),
('H144', '000_0'),
('H145', '000_0'),
('H146', '000_0'),
('H146', '400_1'),
('H211', '500_1'),
('H214', '500_1'),
('H263', '5005_1'),
('H315', '545_1'),
('H316', '545_1'),
('H411', '5455_1'),
('H412', '5455_1'),
('H412', '545_1'),
('H502', '545_1'),
('H503', '545_1'),
('H511', '545_1'),
('H512', '545_1'),
('H513', '545_1'),
('H521', '545_1'),
('H522', '545_1'),
('H523', '545_1'),
('H532', '545_1'),
('H533', '545_1'),
('H710', '500_1'),
('H712', '500_1'),
('H805', '545_1'),
('H811', '000_0'),
('H820', '656_1'),
('H822', '656_1'),
('H832', '545_1'),
('H833', '545_1'),
('H841', '656_1'),
('H853', '500_1'),
('H855', '500_1'),
('H856', '440_60'),
('H856', '545_60'),
('H858', '440_1'),
('H867', '500_1'),
('J315', '545_1'),
('K315', '545_1'),
('K465', '540_60'),
('K960', '5455_60'),
('P261', 'P261AT01'),
('P261', 'P261A_01'),
('P261', 'P261CT01'),
('P263', 'P263A_01'),
('P263', 'P263B_01'),
('P263', 'P263C_01'),
('P263', 'P263C_02'),
('P263', 'P264A_01'),
('P263', 'P963AT07'),
('P264', 'P264AT01'),
('P264', 'P264A_01'),
('P264', 'P264CT01'),
('P860', 'P860AT05'),
('P860', 'P860A_05'),
('P910', 'P910AT05'),
('P910', 'P910AT07'),
('P910', 'P910A_05'),
('P910', 'P910A_07'),
('P916', 'P916AT05'),
('P916', 'P916AT07'),
('P916', 'P916A_05'),
('P917', 'P917AT07'),
('P917', 'P917A_05'),
('P960', 'P960AT05'),
('P960', 'P960AT07'),
('P960', 'P960A_05'),
('P960', 'P960A_07'),
('P963', 'P963A_05'),
('P967', 'P967AT05'),
('P967', 'P967AT07'),
('P967', 'P967A_05'),
('P968', 'P968AT07'),
('P968', 'P968A_05'),
('R212', '500_1'),
('R263', '500_1'),
('R467', '450_120'),
('S263', '500_1'),
('Z000', '400_1'),
('Z000', '440_1'),
('Z000', '500_1'),
('Z000', '540_1'),
('Z000', '545_1'),
('Z000', '600_1'),
('Z000', '640_1'),
('Z000', '656_1');

-- --------------------------------------------------------

--
-- Table structure for table `sap_qa1`
--

CREATE TABLE IF NOT EXISTS `sap_qa1` (
  `MRUnit` varchar(8) DEFAULT NULL,
  `MoveInDate` varchar(10) DEFAULT NULL,
  `MoveOutDate` varchar(10) DEFAULT NULL,
  `Installation` int(10) DEFAULT NULL,
  `RateCategory` varchar(10) DEFAULT NULL,
  `Equipment` int(18) DEFAULT NULL,
  `RegisterGroup` varchar(8) DEFAULT NULL,
  `AMCG` int(4) DEFAULT NULL,
  `LogicalDeviceNo` int(18) DEFAULT NULL,
  `Contract` int(10) DEFAULT NULL,
  `ContractAccount` int(12) DEFAULT NULL,
  `BusinessPartner` int(10) DEFAULT NULL,
  `Inactive` varchar(1) DEFAULT NULL,
  `Material` varchar(6) DEFAULT NULL,
  `SerialNumber` int(18) DEFAULT NULL,
  `ValidTo` varchar(10) DEFAULT NULL,
  `ValidFrom` varchar(10) DEFAULT NULL,
  `BillingClass` varchar(4) DEFAULT NULL,
  `SystemStatus` varchar(4) DEFAULT NULL,
  `Premise` int(10) DEFAULT NULL,
  `RateType` varchar(8) DEFAULT NULL,
  `BillingOrderExists` varchar(4) DEFAULT NULL,
  `MeterReadOrderExists` varchar(4) DEFAULT NULL,
  `MeterReadingDate` varchar(10) DEFAULT NULL,
  `MeterReadingReason` varchar(4) DEFAULT NULL,
  `MultipleAllocation` varchar(4) DEFAULT NULL,
  `MeterReadingType` varchar(4) DEFAULT NULL,
  `MeterReadingStatus` varchar(4) DEFAULT NULL,
  `MeterReadingRecorded` varchar(10) DEFAULT NULL,
  `SAPUpdateDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sap_stage`
--

CREATE TABLE IF NOT EXISTS `sap_stage` (
  `MRUnit` varchar(8) DEFAULT NULL,
  `MoveInDate` varchar(10) DEFAULT NULL,
  `MoveOutDate` varchar(10) DEFAULT NULL,
  `Installation` int(10) DEFAULT NULL,
  `RateCategory` varchar(10) DEFAULT NULL,
  `Equipment` int(18) DEFAULT NULL,
  `RegisterGroup` varchar(8) DEFAULT NULL,
  `AMCG` int(4) DEFAULT NULL,
  `LogicalDeviceNo` int(18) DEFAULT NULL,
  `Contract` int(10) DEFAULT NULL,
  `ContractAccount` int(12) DEFAULT NULL,
  `BusinessPartner` int(10) DEFAULT NULL,
  `Inactive` varchar(1) DEFAULT NULL,
  `Material` varchar(6) DEFAULT NULL,
  `SerialNumber` int(18) DEFAULT NULL,
  `ValidTo` varchar(10) DEFAULT NULL,
  `ValidFrom` varchar(10) DEFAULT NULL,
  `BillingClass` varchar(4) DEFAULT NULL,
  `SystemStatus` varchar(4) DEFAULT NULL,
  `Premise` int(10) DEFAULT NULL,
  `RateType` varchar(8) DEFAULT NULL,
  `BillingOrderExists` varchar(4) DEFAULT NULL,
  `MeterReadOrderExists` varchar(4) DEFAULT NULL,
  `MeterReadingDate` varchar(10) DEFAULT NULL,
  `MeterReadingReason` varchar(4) DEFAULT NULL,
  `MultipleAllocation` varchar(4) DEFAULT NULL,
  `MeterReadingType` varchar(4) DEFAULT NULL,
  `MeterReadingStatus` varchar(4) DEFAULT NULL,
  `MeterReadingRecorded` varchar(10) DEFAULT NULL,
  `SAPUpdateDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(65) NOT NULL,
  `password` varchar(65) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'user1', 'adea4e7b8b53afef1943d93dcd062b1d'),
(2, 'user2', '0c5ef10f35922edbca0899c6918d8e61'),
(3, 'user3', '1e834b7d57e0ad4fb99fa2e5304b09c7'),
(4, 'user4', 'f1044a42e7c3b63b222514b83d18de51 '),
(5, 'user5', 'b525c24eb7b489f9a6410014cc39f260'),
(6, 'user6', '260f44528034900056aa14ea8c706b18'),
(7, 'user7', '452eb16b89b565481e54c759c3c86597'),
(8, 'user8', '8ee18b8e0711c6668e643ed0eccc5237'),
(9, 'user9', '28e571a5567bc9ec97fe8a1ed99d2584'),
(10, 'user10', 'd90d40e7f0478db87388e3101bb2d5e6'),
(11, 'user11', 'f370c185fe3ea079f4a34bb294d59804'),
(12, 'user12', '066883fbe6c509de3103f956dea3dfc7'),
(13, 'user13', '4966851b2f9a91b487256488acf36828'),
(14, 'user14', 'c2187b812b85f4585b9ea53ab88f7321'),
(15, 'user15', '839ef41df7bc9b803d8e1e24a35767ba'),
(16, 'user16', 'f9605c1a761d856c643f65fef9c97fa3'),
(17, 'user17', 'a5d6400cf586ffa5a0c302951ee34e29'),
(18, 'user18', '6c9d223974ea3eebad8be9d998805592'),
(19, 'user19', 'e152f299b83e47275f3020d9143549e9'),
(20, 'user20', '5d4d8cfbae20ab22d21acd0b230508e5'),
(21, 'user21', 'b44105572c7967c4ab5889e707a8d06f '),
(22, 'user22', 'a3d8136ffdcda968a1d6d56ca58451fd'),
(23, 'user23', 'a5d6400cf586ffa5a0c302951ee34e29');
(24, 'user24', 'a5d6400cf586ffa5a0c302951ee34e29');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

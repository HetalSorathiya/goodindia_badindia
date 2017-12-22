-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 08, 2014 at 08:28 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pratichi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login`
--

CREATE TABLE IF NOT EXISTS `tbl_login` (
  `lgn_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key of login table ',
  `lgn_email` varchar(255) NOT NULL COMMENT 'Email of the user',
  `lgn_password` varchar(255) NOT NULL COMMENT 'MD5 Password of user',
  `lgn_created` datetime NOT NULL COMMENT 'Created date of the Login ',
  `lgn_modified` datetime NOT NULL COMMENT 'Modified date of the login ',
  `lgn_active` tinyint(2) NOT NULL COMMENT 'Flag 0 =>Inactive , 1 => Active',
  PRIMARY KEY (`lgn_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_login`
--

INSERT INTO `tbl_login` (`lgn_id`, `lgn_email`, `lgn_password`, `lgn_created`, `lgn_modified`, `lgn_active`) VALUES
(1, 'wikitudedev@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2014-05-22 10:32:22', '2014-05-22 10:32:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_metadata`
--

CREATE TABLE IF NOT EXISTS `tbl_metadata` (
  `mtd_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key of the table',
  `mtd_key` varchar(255) NOT NULL COMMENT 'Key variable of pair',
  `mtd_value` varchar(255) NOT NULL COMMENT 'Value variable of pair',
  PRIMARY KEY (`mtd_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table to store Name and Key Value pair' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tbl_metadata`
--

INSERT INTO `tbl_metadata` (`mtd_id`, `mtd_key`, `mtd_value`) VALUES
(1, 'ADMIN_FROM_EMAIL', 'wikitudedev@gmail.com'),
(2, 'ADMIN_FROM_NAME', 'ADMIN DEMO'),
(3, 'ADMIN_SIGN', 'Thanks,Demo'),
(4, 'ADMIN_REPLY', 'wikitudedev@gmail.com'),
(5, 'PAGE_RECORD', '15'),
(7, 'ADMIN_REPLY_NAME', 'ADMIN');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.10-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table auth.role
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rolename` varchar(50) DEFAULT NULL,
  `created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table auth.role: ~3 rows (approximately)
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` (`id`, `rolename`, `created`) VALUES
	(1, 'user', '2020-12-12 05:27:39'),
	(2, 'Staff', '2020-12-12 05:27:57'),
	(3, 'Admin', '2020-12-12 05:28:00');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;

-- Dumping structure for table auth.rolepermissions
CREATE TABLE IF NOT EXISTS `rolepermissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleid` int(11) DEFAULT NULL,
  `item` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `roleid` (`roleid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table auth.rolepermissions: ~0 rows (approximately)
/*!40000 ALTER TABLE `rolepermissions` DISABLE KEYS */;
INSERT INTO `rolepermissions` (`id`, `roleid`, `item`, `action`, `created`) VALUES
	(1, 2, 'schedule', 'view', '2020-12-12 05:28:26');
/*!40000 ALTER TABLE `rolepermissions` ENABLE KEYS */;

-- Dumping structure for table auth.userrole
CREATE TABLE IF NOT EXISTS `userrole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`roleid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table auth.userrole: ~1 rows (approximately)
/*!40000 ALTER TABLE `userrole` DISABLE KEYS */;
INSERT INTO `userrole` (`id`, `userid`, `roleid`, `created`) VALUES
	(1, 7, 2, '2020-12-12 05:29:14');
/*!40000 ALTER TABLE `userrole` ENABLE KEYS */;

-- Dumping structure for table auth.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registered` timestamp NULL DEFAULT current_timestamp(),
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `verificationcode` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table auth.users: ~7 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `registered`, `email`, `password`, `firstname`, `lastname`, `verificationcode`) VALUES
	(1, '2020-12-12 05:01:00', '1', 'An5Vt2kpvm2o2', '2', '3', 'bsEqviQvhk1iI2Oq6j0M'),
	(2, '2020-12-12 05:01:57', '2', 'An5Vt2kpvm2o2', '2', '3', 'hmqq9H4AJNMwYtePkRy9'),
	(3, '2020-12-12 05:02:28', '3', 'An5Vt2kpvm2o2', '2', '3', 'dtaMg2thIe0LIg17eB1V'),
	(4, '2020-12-12 05:05:30', '4@just.dance', 'An5Vt2kpvm2o2', '2', '3', 'F1IYWB7T7fwu3CpaMe5E'),
	(5, '2020-12-12 05:15:16', '5@just.dance', 'An5Vt2kpvm2o2', '2', '3', 'ciMFzGXv38HjKNK5aLws'),
	(6, '2020-12-12 05:17:52', '6@just.dance', 'An5Vt2kpvm2o2', '2', '3', 'FvnZRG6C8VSYcRcWp3ce'),
	(7, '2020-12-12 05:18:52', '7@just.dance', 'An5Vt2kpvm2o2', '2', '3', 'bPOHkPdz4MdiFJji7adk');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

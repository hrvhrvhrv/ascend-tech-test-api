/*
SQLyog Ultimate v13.2.0 (64 bit)
MySQL - 10.5.8-MariaDB : Database - library
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `bookCollection` */

DROP TABLE IF EXISTS `bookCollection`;

CREATE TABLE `bookCollection` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `libraryId` int(10) unsigned NOT NULL,
  `bookId` int(10) unsigned NOT NULL,
  `dateAdded` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('available','checkedOut','reserved') NOT NULL DEFAULT 'available',
  PRIMARY KEY (`id`),
  KEY `libraryFK` (`libraryId`),
  KEY `bookFK` (`bookId`),
  CONSTRAINT `bookCollectionBookFK` FOREIGN KEY (`bookId`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bookCollectionLibraryFK` FOREIGN KEY (`libraryId`) REFERENCES `libraries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `books` */

DROP TABLE IF EXISTS `books`;

CREATE TABLE `books` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bookName` varchar(512) NOT NULL,
  `authorFirstName` varchar(32) DEFAULT NULL,
  `authorLastName` varchar(32) NOT NULL,
  `publishedYear` year(4) NOT NULL,
  `description` text DEFAULT NULL,
  `imageRef` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `borrowing` */

DROP TABLE IF EXISTS `borrowing`;

CREATE TABLE `borrowing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `collectionId` int(10) unsigned NOT NULL,
  `borrowedDate` datetime NOT NULL,
  `dueDate` date NOT NULL,
  `returnedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`userId`),
  KEY `collection` (`collectionId`),
  CONSTRAINT `borrowingBookCollectionFK` FOREIGN KEY (`collectionId`) REFERENCES `bookCollection` (`id`),
  CONSTRAINT `borrowingUserFK` FOREIGN KEY (`userId`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `libraries` */

DROP TABLE IF EXISTS `libraries`;

CREATE TABLE `libraries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `libraryName` varchar(64) NOT NULL,
  `address` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(32) NOT NULL,
  `lastName` varchar(32) NOT NULL,
  `bookLimit` int(10) unsigned NOT NULL DEFAULT 5,
  `dateRegistered` datetime NOT NULL DEFAULT current_timestamp(),
  `registeredLibrary` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `libID` (`registeredLibrary`),
  CONSTRAINT `userLibraryFK` FOREIGN KEY (`registeredLibrary`) REFERENCES `libraries` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

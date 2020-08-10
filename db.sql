/*
SQLyog Community v13.1.2 (64 bit)
MySQL - 10.3.23-MariaDB-log-cll-lve : Database - miftahla_toko
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`miftahla_toko` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `miftahla_toko`;

/*Table structure for table `t_book` */

DROP TABLE IF EXISTS `t_book`;

CREATE TABLE `t_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(32) CHARACTER SET latin1 DEFAULT NULL,
  `title` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `author` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `publish_year` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_ISBN` (`isbn`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `t_book` */

insert  into `t_book`(`id`,`isbn`,`title`,`author`,`publish_year`) values 
(16,'978-0439023498','The Hunger Games (Book 2)','Suzanne Collins',2009),
(17,'9786026486356','Mindset','Carol. S Dweck, PH.D',2019);

/*Table structure for table `t_code` */

DROP TABLE IF EXISTS `t_code`;

CREATE TABLE `t_code` (
  `code_name` varchar(20) CHARACTER SET latin1 NOT NULL,
  `month` varchar(2) NOT NULL,
  `year` varchar(4) NOT NULL,
  `last_counter` int(11) DEFAULT 0,
  PRIMARY KEY (`code_name`,`month`,`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `t_code` */

insert  into `t_code`(`code_name`,`month`,`year`,`last_counter`) values 
('TRANSACTION_CODE','08','2020',38);

/*Table structure for table `tt_price` */

DROP TABLE IF EXISTS `tt_price`;

CREATE TABLE `tt_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fk_book_id` (`book_id`),
  CONSTRAINT `fk_book_id` FOREIGN KEY (`book_id`) REFERENCES `t_book` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `tt_price` */

/*Table structure for table `tt_transaction_detail` */

DROP TABLE IF EXISTS `tt_transaction_detail`;

CREATE TABLE `tt_transaction_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `transaction_header_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,0) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_price_id` (`price_id`),
  KEY `FK_transaction_header_id` (`transaction_header_id`),
  CONSTRAINT `FK_price_id` FOREIGN KEY (`price_id`) REFERENCES `tt_price` (`id`),
  CONSTRAINT `FK_transaction_header_id` FOREIGN KEY (`transaction_header_id`) REFERENCES `tt_transaction_header` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Data for the table `tt_transaction_detail` */

/*Table structure for table `tt_transaction_header` */

DROP TABLE IF EXISTS `tt_transaction_header`;

CREATE TABLE `tt_transaction_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_code` varchar(20) DEFAULT NULL,
  `customer_name` varchar(120) DEFAULT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `grand_total` decimal(10,0) DEFAULT NULL,
  `admin_id` varchar(64) DEFAULT NULL,
  `status` varchar(12) DEFAULT 'open',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`transaction_code`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

/*Data for the table `tt_transaction_header` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(120) DEFAULT NULL,
  `password` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`id`,`email`,`password`) values 
(1,'amat.miftakhudin@gmail.com','83b44aac5b87d0b5bdfc18a922456d4e');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

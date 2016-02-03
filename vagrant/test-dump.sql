/*
SQLyog Trial v12.18 (64 bit)
MySQL - 5.6.27-0ubuntu0.14.04.1 : Database - test
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`test` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `test`;

/*Table structure for table `vendor` */

DROP TABLE IF EXISTS `vendor`;

CREATE TABLE `vendor` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `vendor` */

insert  into `vendor`(`id`,`name`) values

(1,'FooBar');

/*Table structure for table `vendor_schedule` */

DROP TABLE IF EXISTS `vendor_schedule`;

CREATE TABLE `vendor_schedule` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint(20) DEFAULT NULL,
  `weekday` tinyint(4) DEFAULT NULL,
  `all_day` tinyint(3) DEFAULT NULL,
  `start_hour` time DEFAULT NULL,
  `stop_hour` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vendor` (`vendor_id`),
  CONSTRAINT `fk_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `vendor_schedule` */

insert  into `vendor_schedule`(`id`,`vendor_id`,`weekday`,`all_day`,`start_hour`,`stop_hour`) values

(1,1,2,0,'19:00:00','22:00:00'),

(2,1,3,0,'19:00:00','22:00:00'),

(3,1,4,0,'19:00:00','22:00:00'),

(4,1,5,0,'19:00:00','22:00:00'),

(5,1,6,0,'11:00:00','14:00:00'),

(6,1,6,0,'19:00:00','23:59:59'),

(7,1,7,1,NULL,NULL);

/*Table structure for table `vendor_special_day` */

DROP TABLE IF EXISTS `vendor_special_day`;

CREATE TABLE `vendor_special_day` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint(1) DEFAULT NULL,
  `special_date` date DEFAULT NULL,
  `event_type` enum('opened','closed') CHARACTER SET latin1 DEFAULT NULL,
  `all_day` tinyint(1) DEFAULT NULL,
  `start_hour` time DEFAULT NULL,
  `stop_hour` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vendor_special` (`vendor_id`),
  CONSTRAINT `fk_vendor_special` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `vendor_special_day` */

insert  into `vendor_special_day`(`id`,`vendor_id`,`special_date`,`event_type`,`all_day`,`start_hour`,`stop_hour`) values

(1,1,'2015-12-24','closed',1,NULL,NULL),

(2,1,'2015-12-25','closed',1,NULL,NULL),

(3,1,'2015-12-26','opened',0,'19:00:00','22:00:00'),

(4,1,'2015-12-27','opened',0,'19:00:00','22:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

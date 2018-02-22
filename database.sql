/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 10.1.13-MariaDB : Database - lifecare
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cities` */

DROP TABLE IF EXISTS `cities`;

CREATE TABLE `cities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cities` */

/*Table structure for table `countries` */

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sortname` varchar(9) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(450) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `phonecode` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `countries` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `hospitals` */

DROP TABLE IF EXISTS `hospitals`;

CREATE TABLE `hospitals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hospitals` */

insert  into `hospitals`(`id`,`title`,`is_active`,`created_at`,`updated_at`) values (1,'Hospital 1',1,'2018-02-22 16:39:19','2018-02-22 16:39:19');
insert  into `hospitals`(`id`,`title`,`is_active`,`created_at`,`updated_at`) values (2,'Hospital 2',1,'2018-02-22 16:39:19','2018-02-22 16:39:19');

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_at_index` (`queue`,`reserved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `professions` */

DROP TABLE IF EXISTS `professions`;

CREATE TABLE `professions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `professions` */

insert  into `professions`(`id`,`title`,`is_active`,`created_at`,`updated_at`) values (1,'Profession 1',1,'2018-02-22 16:40:22','2018-02-22 16:40:22');
insert  into `professions`(`id`,`title`,`is_active`,`created_at`,`updated_at`) values (2,'Profession 2',1,'2018-02-22 16:40:22','2018-02-22 16:40:22');

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `config_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `config_value` text COLLATE utf8_unicode_ci NOT NULL,
  `is_encoded` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_config_key_unique` (`config_key`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `settings` */

insert  into `settings`(`id`,`config_key`,`config_value`,`is_encoded`,`created_at`,`updated_at`) values (1,'core.application','LifeCare',0,'2016-09-26 10:30:32','2016-09-26 10:30:32');
insert  into `settings`(`id`,`config_key`,`config_value`,`is_encoded`,`created_at`,`updated_at`) values (3,'email.support','support@lifecare.com',0,'2016-09-26 10:30:32','2016-09-26 10:30:32');
insert  into `settings`(`id`,`config_key`,`config_value`,`is_encoded`,`created_at`,`updated_at`) values (4,'email.contact','contact@lifecare.com',0,'2016-09-26 10:30:32','2016-09-26 10:30:32');

/*Table structure for table `states` */

DROP TABLE IF EXISTS `states`;

CREATE TABLE `states` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `states_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `states` */

/*Table structure for table `user_activity` */

DROP TABLE IF EXISTS `user_activity`;

CREATE TABLE `user_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `event_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `event_data` text COLLATE utf8_unicode_ci,
  `is_encoded` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_activity_ibfk_1` (`user_id`),
  CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `user_activity` */

/*Table structure for table `user_devices` */

DROP TABLE IF EXISTS `user_devices`;

CREATE TABLE `user_devices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `device_type` enum('ios','android') COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Device Token for Notifications',
  `auth_token` text COLLATE utf8_unicode_ci COMMENT 'JWT Auth token',
  PRIMARY KEY (`id`),
  KEY `user_devices_ibfk_1` (`user_id`),
  CONSTRAINT `user_devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `user_devices` */

insert  into `user_devices`(`id`,`user_id`,`device_type`,`device_token`,`auth_token`) values (9,3,NULL,'','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUxOTMxMDkzNiwiZXhwIjoxNTUwODQ2OTM2LCJuYmYiOjE1MTkzMTA5MzYsImp0aSI6IkNNYnNSNzdHc2l4blcySGwifQ.H2mqMjyWaA14NRm7TbPylTXwcgWNMaGUIH7h7tXC2z4');

/*Table structure for table `user_meta` */

DROP TABLE IF EXISTS `user_meta`;

CREATE TABLE `user_meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'null',
  `key` varchar(255) NOT NULL,
  `value` text,
  `grouping` enum('profile','application','driver') NOT NULL DEFAULT 'profile',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key` (`key`(191)),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_meta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `user_meta` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL DEFAULT '2' COMMENT 'Default normal user',
  `hospital_id` int(10) unsigned NOT NULL,
  `profession_id` int(10) unsigned NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `city` int(11) unsigned NOT NULL DEFAULT '0',
  `state` int(11) unsigned NOT NULL DEFAULT '0',
  `country` int(11) DEFAULT '231',
  `profile_picture` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_verification` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `sms_verification` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Fully activated account.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `state` (`state`),
  KEY `hospital_id` (`hospital_id`),
  KEY `profession_id` (`profession_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`profession_id`) REFERENCES `professions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`role_id`,`hospital_id`,`profession_id`,`first_name`,`last_name`,`email`,`password`,`phone`,`address`,`city`,`state`,`country`,`profile_picture`,`remember_token`,`email_verification`,`sms_verification`,`is_active`,`created_at`,`updated_at`,`deleted_at`) values (3,3,1,1,'test','test','normal@appmaisters.com','$2y$10$Yikv4BRmHzKUoibxIEmJG.hSyxAFbmBla.YUZaM7d0Pc/3l/8eEmq','+18324561598','',0,0,231,'',NULL,'1','1',1,'2018-02-22 12:23:56','2018-02-22 14:49:06',NULL);
insert  into `users`(`id`,`role_id`,`hospital_id`,`profession_id`,`first_name`,`last_name`,`email`,`password`,`phone`,`address`,`city`,`state`,`country`,`profile_picture`,`remember_token`,`email_verification`,`sms_verification`,`is_active`,`created_at`,`updated_at`,`deleted_at`) values (5,3,1,1,'Physician','Account','normal2@appmaisters.com','$2y$10$vDNcYy/VABvG13EfJsY89eIyXnBcPpQxojJU7UV.RI0PR40nEwbye','+18324561599','',0,0,231,'',NULL,'1','1',1,'2018-02-22 12:30:58','2018-02-22 12:30:59',NULL);
insert  into `users`(`id`,`role_id`,`hospital_id`,`profession_id`,`first_name`,`last_name`,`email`,`password`,`phone`,`address`,`city`,`state`,`country`,`profile_picture`,`remember_token`,`email_verification`,`sms_verification`,`is_active`,`created_at`,`updated_at`,`deleted_at`) values (6,3,2,2,'Physician','Account','normal3@appmaisters.com','$2y$10$Ovnd3jFrsRVhPUht4bWeZeGuHcZl3soI6JejbmRt8r9QOhasu5sqi','+18324561601','',0,0,231,'','wWmQ52YQXqHKlIY2nA0uXdf4ZixzUIhZ96ar6vwCh3uz309JyWVILm3d9D1S','1','1',1,'2018-02-22 12:31:11','2018-02-22 14:32:36',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

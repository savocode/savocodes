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
  `description` text COLLATE utf8mb4_unicode_ci,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(11,9) DEFAULT '0.000000000',
  `longitude` decimal(11,9) DEFAULT '0.000000000',
  `timing_open` time DEFAULT NULL,
  `timing_close` time DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_24_7_phone` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zip_code` (`zip_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hospitals` */

insert  into `hospitals`(`id`,`title`,`description`,`address`,`location`,`zip_code`,`latitude`,`longitude`,`timing_open`,`timing_close`,`phone`,`is_24_7_phone`,`is_active`,`created_at`,`updated_at`,`deleted_at`) values (1,'Hospital 1','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.','','Alabamma','12345','0.000000000','0.000000000','09:00:00','11:30:00',NULL,0,1,'2018-02-22 16:39:19','2018-02-22 16:39:19',NULL),(2,'Hospital 2','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.','','Jamaica','12345','0.000000000','0.000000000','09:00:00','17:00:00',NULL,0,1,'2018-02-22 16:39:19','2018-02-22 16:39:19',NULL);

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

insert  into `password_resets`(`email`,`token`,`created_at`) values ('XpMmNFaatDV5epgzjLUxQdmD/sPJl3SAaNIRkbK/N/w=','$2y$10$tyx3CRArsIu5hQ81sJ/EPeUMaFixNJofJ2Mcfry4NkWg4OuZ.UN26','2018-03-15 11:32:09');

/*Table structure for table `professions` */

DROP TABLE IF EXISTS `professions`;

CREATE TABLE `professions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `professions` */

insert  into `professions`(`id`,`title`,`is_active`,`created_at`,`updated_at`) values (1,'Patient',1,'2018-02-22 16:40:22','2018-02-22 16:40:22'),(2,'Family',1,'2018-02-22 16:40:22','2018-02-22 16:40:22'),(3,'Case manager',1,'2018-02-22 16:40:22','2018-02-22 16:40:22'),(4,'Social Worker',1,'2018-02-22 16:40:22','2018-02-22 16:40:22'),(5,'Power of attorney',1,'2018-02-22 16:40:22','2018-02-22 16:40:22'),(6,'Other',1,'2018-02-22 16:40:22','2018-02-22 16:40:22');

/*Table structure for table `referral_status_histories` */

DROP TABLE IF EXISTS `referral_status_histories`;

CREATE TABLE `referral_status_histories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `referral_id` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned NOT NULL COMMENT 'Employee ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Updated status',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `referrral_id` (`referral_id`),
  CONSTRAINT `referral_status_histories_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `referral_status_histories_ibfk_2` FOREIGN KEY (`referral_id`) REFERENCES `referrals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `referral_status_histories` */

insert  into `referral_status_histories`(`id`,`referral_id`,`created_by`,`status`,`created_at`) values (1,15,3,0,'2018-03-01 12:58:00'),(2,16,3,0,'2018-03-01 13:16:34'),(3,17,3,0,'2018-03-02 07:21:15'),(4,19,3,0,'2018-03-14 13:14:56');

/*Table structure for table `referrals` */

DROP TABLE IF EXISTS `referrals`;

CREATE TABLE `referrals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` int(10) unsigned NOT NULL,
  `referred_by` int(10) unsigned NOT NULL COMMENT 'Doctor ID',
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosis` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `hospital_id` (`hospital_id`),
  KEY `referred_by` (`referred_by`),
  CONSTRAINT `referrals_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `referrals_ibfk_2` FOREIGN KEY (`referred_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `referrals` */

insert  into `referrals`(`id`,`hospital_id`,`referred_by`,`first_name`,`last_name`,`age`,`phone`,`diagnosis`,`status`,`created_at`,`updated_at`) values (15,1,3,'Paul','Pogba','23','0234212312','Hamstring Injury',0,'2018-03-01 12:58:00','2018-03-01 12:58:00'),(16,2,3,'d2guYgx6YrCshEj/brv5yg==','CUarce0CTGWM5r6gVrWcwA==','DxhWUGCxcRx0ACNYbtmE0Q==','rnwkMHRSHOhzXORnaO2vJA==','eUHDEzFEOX87ChKJi1QI7w==',0,'2018-03-01 13:16:34','2018-03-01 13:16:34'),(17,2,3,'Jfbg1MkKeYZs/04/wRCrww==','sB8kyuMB+Yb19KIldql9RQ==','ZYdg4gOSiVErSd7xlzBZWg==','wDzQiGohZzCf5r2FQUcHpg==','D+pWmlFxip0GdKR6ujaQBg==',0,'2018-03-02 07:21:15','2018-03-02 07:21:15'),(18,1,5,'test','','','',NULL,0,'2018-03-12 07:59:47','2018-03-12 07:59:47'),(19,2,3,'KNRXSzKvR0t0QehhUo0uDw==','xGQEhp1bXPuh41LOtf76Gw==','ES6vZ+8q4j0PSTIhe+mEWQ==','T4rUf8QkSnjX3SKkY7r6Bw==','5bvdpYTNhJQG9ihGsOVWcw==',0,'2018-03-14 13:14:55','2018-03-14 13:14:55');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `settings` */

insert  into `settings`(`id`,`config_key`,`config_value`,`is_encoded`,`created_at`,`updated_at`) values (1,'core.application','LifeCare',0,'2016-09-26 10:30:32','2016-09-26 10:30:32'),(3,'email.support','support@lifecare.com',0,'2016-09-26 10:30:32','2016-09-26 10:30:32'),(4,'email.contact','contact@lifecare.com',0,'2016-09-26 10:30:32','2016-09-26 10:30:32'),(5,'cms.about_us','LifeCare Health partners, a leading healthcare services provider, is dedicated to improving the quality of life for patients and maximizing their potential for hearing and recovery. Powered by passionate care teams, LifeCare specializes in helping patients with expert, aggressive medical care in warm, caring environments.',0,'2018-02-26 18:06:21','2018-02-26 18:06:21'),(6,'cms.criteria','\r\n[{\"title\":\"Respiratory Failure\",\"body\":\"<ul>\\n    <li>Active daily physician assessment\\/intervention.<\\/li>\\n    <li>Aggressive pulmonary hygiene.<\\/li>\\n    <li>Nebulizer treatments at least Q6 and PRN.<\\/li>\\n    <li>Chest tube management.<\\/li>\\n    <li>Supplemental FiO2 to maintain SaO2 >90%.<\\/li>\\n    <li>Invasive vent support with weaning potential.<\\/li>\\n    <li>Non-invasive support with BiPAP \\/CPAP continuously or intermittently.<\\/li>\\n    <li>Respiratory therapist available 24\\/7.<\\/li>\\n    <li>Trach care and management.<\\/li>\\n    <li>Continuous O2 Monitoring.<\\/li>\\n    <li>Pulmonology Consult with active management.<\\/li>\\n    <li>24 hour RN care.<\\/li>\\n    <li>Antibiotic coverage.<\\/li>\\n    <li>Chest physiotherapy.<\\/li>\\n    <li>Frequent lab and\\/or radiology studies.<\\/li>\\n    <li>Patient\\/Family training for home care.<\\/li>\\n    <li>PT\\/OT for oxygen conservation training and strengthening.<\\/li>\\n    <li>Speech training for evaluation and treatment of swallowing disorders.<\\/li>\\n<\\/ul>\"},{\"title\":\"Renal Failure\",\"body\":\"<ul>\\n    <li>Active daily physician assessment\\/intervention.<\\/li>\\n    <li>Nephrology coverage.<\\/li>\\n    <li>Acute renal failure requiring dialysis and monitoring for recovery.<\\/li>\\n    <li>Electrolyte monitoring and management.<\\/li>\\n    <li>Management of metabolic disturbances  with replacement when indicated.<\\/li>\\n    <li>Ultra-filtration to establish a target weight.<\\/li>\\n    <li>Diuresis for volume management.<\\/li>\\n    <li>Acute or Chronic dialysis (both PD and hemo).<\\/li>\\n    <li>Dietitian consult for training with dietary restrictions.<\\/li>\\n    <li>Frequent lab and\\/or radiology studies.<\\/li>\\n<\\/ul>\"},{\"title\":\"Major GI Disturbances\",\"body\":\"<ul>\\n    <li>Bowel rest requiring TPN.<\\/li>\\n    <li>New fistula management.<\\/li>\\n    <li>High output fistula or ostomy requiring<\\/li>\\n    <li>Antibiotic coverage.<\\/li>\\n    <li>Patient\\/Family training for home care.<\\/li>\\n    <li>Dietitian consult for treatment plan and training.<\\/li>\\n<\\/ul>\"},{\"title\":\"Complex Wound Care\",\"body\":\"<ul>\\n    <li>Complex wound dehiscence requiring extensive wound care and monitoring at least Q12 hours by Certified Wound Care team and RN care.<\\/li>\\n    <li>Wounds with bone and\\/or tendon exposure.<\\/li>\\n    <li>Osteomyelitis with prolonged antibiotic coverage and wound care.<\\/li>\\n    <li>Large necrotic wounds requiring frequent debridement.<\\/li>\\n    <li>Drain and\\/or wound suction management system.<\\/li>\\n    <li>Multiple Stage III \\u2013 IV wounds.<\\/li>\\n    <li>Guillotine-style amputations.<\\/li>\\n    <li>Dietitian consult for nutritional support to aid in healing.<\\/li>\\n    <li>PT\\/OT consult for strengthening and training.<\\/li>\\n<\\/ul>\"},{\"title\":\"Cardiovascular Conditions\",\"body\":\"<ul>\\n    <li>Endocarditis requiring prolonged IV antibiotic therapy and acute care with cardiac monitoring.<\\/li>\\n    <li>Heart failure requiring daily adjustment of diuretic therapy, fluids and\\/or electrolyte replacement and\\/or LVAD* assistance.<\\/li>\\n    <li>Heart failure with pulmonary hypertension requiring long-term IV vasodilator therapy and continued oxygen support (greater than 40%).<\\/li>\\n    <li>New onset cardiac dysfunction requiring active monitoring and medication management.<\\/li>\\n    <li>Complications post heart transplant or post cardiac surgery.<\\/li>\\n    <li>Cardiac dysthymias that requiring monitoring with possible intervention and are at risk for requiring Rapid Response Team intervention.<\\/li>\\n    <li>Dietitian consult for training on dietary restrictions.<\\/li>\\n    <li>PT\\/OT for strengthening and training.<\\/li>\\n<\\/ul>\"},{\"title\":\"Trauma\",\"body\":\"<ul>\\n    <li>Active daily physician monitoring and intervention.<\\/li>\\n    <li>Acute care by certified TBI team with a focus on recovery*<\\/li>\\n    <li>Multiple fractures requiring monitoring and management to prevent additional trauma or send back to the STAC.<\\/li>\\n    <li>Consult for wound care by certified wound care clinicians.<\\/li>\\n    <li>PT and OT consult for strengthening and training.<\\/li>\\n    <li>Speech consult to add in communication recovery.<\\/li>\\n    <li>Dietitian consult for nutritional support to aid in healing.<\\/li>\\n    <li>Neurology consult for treatment plan and to follow.<\\/li>\\n<\\/ul>\"},{\"title\":\"LEVELS OF CARE\",\"body\":\"<h3>Short Term Acute<\\/h3>\\n<p>Responsible for evaluating patient and establishing a diagnosis, providing appropriate interventions (diagnostic or surgical intervention), forming a treatment plan and initiating the plan. Move to lower level of care when patient is stable and patient is responding to treatment plan.  (Note: Attending physicians round daily.)<\\/p>\\n\\n<h3>Long Term Acute Care<\\/h3>\\n<p>Responsible for continuing a plan of care on an acute care patient who is determined to require at least 20 days continued inpatient acute care with daily physician assessment and support.  These patients would move to lower level of care when patient is stable and care plan can be safely continued without daily rounding by physician.<\\/p>\\n\\n<h3>Acute Rehab<\\/h3>\\n<p>Responsible for providing care with a rehab focus on medically stable patients.  The patient will need to be able to participate in at least 3 hours of therapy each day and be able to demonstrate progress to continue with the program.<\\/p>\\n\\n<h3>Skilled Nursing Facility<\\/h3>\\n<p>Responsible for continuing a plan of care on a stable patient providing that the care plan can be carried out by licensed staff and seen by a physician or physician extender on average once per week (Medicare guidelines stipulate once a month).<\\/p>\\n\\n<h3>Home Health<\\/h3>\\n<p>Responsible for continuing a plan of care on a home bound stable patient providing that the care plan can be carried out by licensed staff or non-licensed staff and is seen by a physician on average once every 2-3 months.<\\/p>\"}]',1,'2018-02-28 11:34:36','2018-02-28 11:34:36');

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
  `2fa` char(6) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '2FA Token',
  PRIMARY KEY (`id`),
  KEY `user_devices_ibfk_1` (`user_id`),
  CONSTRAINT `user_devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=368 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `user_devices` */

insert  into `user_devices`(`id`,`user_id`,`device_type`,`device_token`,`auth_token`,`2fa`) values (15,7,'android','123123341241232131223574533432423423','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjcsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9yZWdpc3RlciIsImlhdCI6MTUxOTM4NzEyMSwiZXhwIjoxNTUwOTIzMTIxLCJuYmYiOjE1MTkzODcxMjEsImp0aSI6IlYzbVhkekxWRzdpWURwTTcifQ.NymxCY0rd5kmBHHHol81lKOPD3eR8g7eXxZd3PPxwAw',NULL),(85,3,'android','1231231231','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMDQzNDMzNywiZXhwIjoxNTUxOTcwMzM3LCJuYmYiOjE1MjA0MzQzMzcsImp0aSI6IlgxbTg4eEFnNlFnMkJsQW8ifQ.t8GSy30aedNjqxZrl2KnlSB8TYXa3s61GGZFqKoMUcM',NULL),(96,3,'android','dFxfWksUH0A:APA91bGPBiHF01pdDlMDOTvC4oyDbgpcK9PAk4GC7vLJ31MmYW2Oa8nMzYvJXpepI78E1_934ovAs0Y4_cPwcoXPrNW7TOCZLpcWru-1bUS9od5CjZ1RrpKfTUwlUZFzyQN1bEsMvCVA','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMDUxNTU4MSwiZXhwIjoxNTUyMDUxNTgxLCJuYmYiOjE1MjA1MTU1ODEsImp0aSI6ImxjelFVcXFkb2trRExxTlYifQ.tap3Q_PGce4ro22j1Z7MLjpU2CMDsE4G9FDpPPuddz0',NULL),(200,3,'android','c4WBLCMmLwk:APA91bE625BaEgfqhE4oI9ZVEwycqul3Kx2ddTc3G_mFCcFrhyNHu_sYXBD4qJPeqgJKDGNQSNRFR3PvB-bUPDXce3FV6ZsybQw7CnHMF-5NW1ORZRO5avcnDDRrz4KOaqbampJR-TTO','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMTA0NTM4MiwiZXhwIjoxNTUyNTgxMzgyLCJuYmYiOjE1MjEwNDUzODIsImp0aSI6ImNiMzdPRU5kWHI4aHFrbTIifQ.-_XwKLi4nLeTbw6ffFw_QsDuzzi-mugHqaUmLRaS1fA',NULL),(211,3,'android','eYmVxflgCuc:APA91bGA1Sho1fw8RnU44g50nHvOJgAchfOjY7dF4vSzBo6ORUslwYf6R0T5ahsChGAjSc-SY9X2w_oiOnPvzq3MFtR2cwsrBsmB3ZL2g_Scm0KfK5te0Bqw0Qw6i6Fq-AfUiiUXyjyf','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMTEwOTQ3MSwiZXhwIjoxNTUyNjQ1NDcxLCJuYmYiOjE1MjExMDk0NzEsImp0aSI6Ik9ncW1Uajh6bDZCZUtwV1cifQ.4S7ZudBag8x5EZDfinOO2wXPooRQJviH2uqZxlrEADE',NULL),(218,3,'android','f-nHSbfRfq4:APA91bFcWDiTSQHbM7yp6eVBfsm_rHXLcT6cNZ1sFcaU1ltaqFpgpO2EfrzCkgTxDxQMiVD-dWn9oKR36C8Eh8SFLTDktLBznEoC9ejSn0p4GSHeTWz3VlhUkgtgWK5T8M74No9qqtRA','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMTExNDI5NSwiZXhwIjoxNTUyNjUwMjk1LCJuYmYiOjE1MjExMTQyOTUsImp0aSI6IlZ0dVpBa2RTeDNOUDIzalUifQ.ItpwJVQVxaPNk8_RRDDb5yJEUd8y-9Q8Z0sOA8ek6zc',NULL),(220,3,'android','c7RH2BTUeNk:APA91bEuARGSOCzh-mIKbksqQRd8J8oS6AbInWKHCOwa9OKkCh-y8paNGvQDADoc5hnaofy8kDvBIdkGogMZ6kOMYZFEGwOUhliMmYCe0-ywTKrhf9ii4MM8wZCbiiLNpYq2pJr6Thhg','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMTExNTkwMSwiZXhwIjoxNTUyNjUxOTAxLCJuYmYiOjE1MjExMTU5MDEsImp0aSI6IlRCcWxnQUQySkxxcWsxMUUifQ.IFaGaDz9_41q8FFlc_8kjJazBxCtmoDaq39BT18O-98',NULL),(233,3,'android','f4Q1obpP2Yo:APA91bEDb-yda0b_5OAfyAoZaJWarifjgJB4KgxM8-AmqsNrEOLDFnEyjNF6f7QSKOM_AQdEYrJGQg2o_MpDAVzDz_fRDjEl93pNAP6Aj9RF6t3tR37ZvBVAM1YKWio8_SnT7TzWbD1b','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMTEyNTUyOSwiZXhwIjoxNTUyNjYxNTI5LCJuYmYiOjE1MjExMjU1MjksImp0aSI6InExUjB1RlEwY3ZrWXd2enYifQ.cPZRH-A25-BDQE2k9XSl1-Eh9w4SmmlM_m7fMUe-2Fk',NULL),(243,3,'android','fpXXZJ7-Eoo:APA91bEn_5e1nnBNF_bMbRFFCZI3AK52P30HHuzkKhP9zNxMFCSDCpWLHgOrrDWyvsc6_3wKB9xvoidxoRP5nTaskIGpI85w1bNeU_qcJ2QLGrAiysbGVBPuW5DqpK6I5iaNdUvDLSPo','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMTEyODc4NCwiZXhwIjoxNTUyNjY0Nzg0LCJuYmYiOjE1MjExMjg3ODQsImp0aSI6Im9TUGREeFlIY3hWVkhvTjIifQ.5wrQb19-48XyPl2M3J1pZa0BYKyjfEGcZmCkvcN-QY4',NULL),(253,3,'android','dywRf-2bI2Y:APA91bGVp50AHfQTtO9ROnShmoSIGuRC1jFVOCAnfoonPlgnascf8AEKzP-ql_0TiuBxQXhZV9Jcghth9GqtKj1VrkTcaO7k2mnuVbFDq-4bzumF3lBuZBN30YP_zt-y1pKJyZCffxN4','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMTE5NDMzMywiZXhwIjoxNTUyNzMwMzMzLCJuYmYiOjE1MjExOTQzMzMsImp0aSI6IklWZ3RGeFRwYmEzdDZWdngifQ.GQbp2ijSmJDs7tI0ApfBrATAzAmVZRCw-cbrPS8b-Ec',NULL),(256,3,'android','capefJacwGE:APA91bEJGcocONVFxsfIL63IdtWNZaamL77JtEKM30lwmIIVTwo83bCnvDdPyhCVY3z8pqr48jHsbdlPrDX1s1bQ_svdO7qFKUPQdJTJ52ekYLCkFDHfxaPM6WeRsISxdSg5lKx6MwXv','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMTE5NTMwMywiZXhwIjoxNTUyNzMxMzAzLCJuYmYiOjE1MjExOTUzMDMsImp0aSI6IlNPM21qOHVJVnRORGlRS1AifQ.SvyM51zwjrDwh34NBK1H5Tk-NaFQNFoKs4B8gAkc4tY',NULL),(270,3,'android','eYXemeIJubU:APA91bGvzKxr9rA5-GC9HkJAMEmj6SMYTumHvS_ztcXQ1-Oy31MVTsYfLgD0-s28FLnDWLGgl5dadNoJzuBoDd-zPRKXwh2dXmCCWEQkd95wI6RYUADq6Wx_pQFCWwO3OjmhJQunLgZt','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMTUzNzcwMCwiZXhwIjoxNTUzMDczNzAwLCJuYmYiOjE1MjE1Mzc3MDAsImp0aSI6Ik9zUGNIWklaVjBMUnQ5TmMifQ.C3xbMqqahfNNLca5i62EXAEHRzA7OOUkeJczGVk82wo',NULL),(316,3,'android','etFiqSmjYp4:APA91bErV82GR18fv7hD0f7FhxSSEI-nquB_k_uifsC5NoLZQOzIHqP2UDc7LwSY4wT-Aj9Ovn7WJ90upMyZE3XrWbOoVBpUr1XF9VQTs004NcV-fYcJ5FAA_OudUhxitVH1kazmKN3t','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS9sb2dpbiIsImlhdCI6MTUyMTY0MzIzOCwiZXhwIjoxNTUzMTc5MjM4LCJuYmYiOjE1MjE2NDMyMzgsImp0aSI6InM4cmhROXhENnhCc0Zubm0ifQ.a0C94zyhKvFhcyfPE67Mwv4UjGgeGg0iJ64uRkltsy4',NULL),(362,3,NULL,'','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS8yZmEtYXV0aCIsImlhdCI6MTUyMjE1NjgyNiwiZXhwIjoxNTIyMTYwNDI2LCJuYmYiOjE1MjIxNTY4MjYsImp0aSI6Ing4cERLUTgzaHZ1ZmlYclQifQ.qR126WE-KGhYkhFaNvCYTivfDgtuqRV2mGxgFFiNrYA',NULL),(365,3,'android','123','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6Ly8xOTIuMTY4LjE2OC4xMTQvbGlmZWNhcmUvcHVibGljL2FwaS92MS8yZmEtYXV0aCIsImlhdCI6MTUyMjE1NzU3NiwiZXhwIjoxNTIyMTYxMTc2LCJuYmYiOjE1MjIxNTc1NzYsImp0aSI6IkEycmpYZ2loSWg2dndnS2QifQ.tmZFIfkoX6UEKrs4ThfaPYrdN5GV6v3ZHnaidYq8tIs',NULL);

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
  `phone` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `city` int(11) unsigned NOT NULL DEFAULT '0',
  `state` int(11) unsigned NOT NULL DEFAULT '0',
  `country` int(11) DEFAULT '231',
  `profile_picture` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_verification` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `sms_verification` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Fully activated account.',
  `2fa` char(6) COLLATE utf8_unicode_ci DEFAULT '1' COMMENT '2FA Code',
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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`role_id`,`hospital_id`,`profession_id`,`first_name`,`last_name`,`email`,`password`,`phone`,`address`,`city`,`state`,`country`,`profile_picture`,`remember_token`,`email_verification`,`sms_verification`,`is_active`,`2fa`,`created_at`,`updated_at`,`deleted_at`) values (3,3,1,1,'2Nv/fXuMzVmmlflMMUJcNA==','2Nv/fXuMzVmmlflMMUJcNA==','XpMmNFaatDV5epgzjLUxQdmD/sPJl3SAaNIRkbK/N/w=','$2y$10$AKEGbCyWDb55LKMpgWMOyuMA0lG6Yrl4nRilaVawd4tVAbkf9bEQ.','3SG/GwHZEFaI/kGpi/nbcg==','',0,0,231,'',NULL,'1','1',1,'377874','2018-02-22 12:23:56','2018-03-28 12:55:52',NULL),(5,3,1,1,'thY4GMij06Xh07eNDOzHfQ==','S0J2NMO+9rvueZmD5+/YNA==','G5BuJapttGQZjXSG2QDFtf8bU729iuZeROjAuWgDRsI=','$2y$10$mLtIPu8CDZSAGX2AgQD50u3xcSj9OZYKaMhIxygXf3B1qfduBBO1i','85cJH0raetwt4KwsiPdBPg==','',0,0,231,'',NULL,'1','1',1,'1','2018-02-22 12:30:58','2018-03-14 14:01:53',NULL),(6,3,2,2,'thY4GMij06Xh07eNDOzHfQ==','S0J2NMO+9rvueZmD5+/YNA==','IUcp0ztgkda530UqGSxPIPCoyNURyjGFFxC5tpaMcMM=','$2y$10$sXBZ1c/BMJZfFogLt/PgPOzRPLTSVN5KX3xUaygmXbiXDtMb96H9u','i+lkzcOSKZ0Cl1pYRnwIrQ==','',0,0,231,'','wWmQ52YQXqHKlIY2nA0uXdf4ZixzUIhZ96ar6vwCh3uz309JyWVILm3d9D1S','1','1',1,'1','2018-02-22 12:31:11','2018-03-14 14:01:53',NULL),(7,3,1,1,'zdZGAa5/33faIxCOqVbVZA==','DQxpZf460tM6UpaG8ver9g==','6gPUQy0qCM1cMcPsHNQUOQ==','$2y$10$cB1bbZhkD9PFKrCbx9sQ3espcVTPbofs7qL3k8hxlvLLrwLPt9aKO','ztRnknpVR8zKIDiSHzeBCg==','',0,0,231,'',NULL,'1','1',1,'1','2018-02-23 11:58:36','2018-03-14 14:01:53',NULL),(8,3,1,1,'hfMIPFbIie9OV+YeraS5BQ==','KUy8wTKLH8G1RN1xFJruyA==','WXUX0PR4a/O9nuXDPCYl3z3Hhaqin2h+ZKGPxqdDKas=','$2y$10$Gu9ZovSRkZ/4ayOEhRygCel15ED2UqsHrqRUPZrCuyRF/76clpQDa','hWkgGA6zd/WSfuV9zJ61fg==','',0,0,231,'',NULL,'1','1',1,'661499','2018-02-23 13:16:06','2018-03-27 12:58:36',NULL),(9,3,1,1,'hfMIPFbIie9OV+YeraS5BQ==','KUy8wTKLH8G1RN1xFJruyA==','U5P2jv3l3BoDeouU/snX7Ga5HY8onRI8EdaztEWpBk4=','$2y$10$CnmKvyUWMNNMYrPAtTkZYOX.WIpx4hASFIgqrjdlO69woWOyMDOs2','A6wtt5/Uk3zXLHi8fvEh3w==','',0,0,231,'',NULL,'1','1',1,'1','2018-02-23 13:25:44','2018-03-14 14:01:53',NULL),(10,3,1,1,'ePqeZtDxCQRSq8cBW80wBw==','XI8ygY18AI4gVUYKUHRdFg==','I4GF3FtSonfSt6UWtkHQQg==','$2y$10$ia.5gEudh5A3K7QsP3UMj.HERghVYAH6UICcbq.7eWa2YnLBeM5pm','hPfxC6NSNtXjEidC2SMETw==','',0,0,231,'',NULL,'1','1',1,'1','2018-02-26 11:38:16','2018-03-14 14:01:53',NULL),(11,3,1,1,'ePqeZtDxCQRSq8cBW80wBw==','XI8ygY18AI4gVUYKUHRdFg==','bEHa+EyHLtvmWzJgZa8PsQ==','$2y$10$tGCsiPu0gLKAXCq0GfANNeviTU/ZOyjyvr4RLe3ooL14IOXTnV4jO','lSKdOfON5SdHWwbRaaQImQ==','',0,0,231,'',NULL,'1','1',0,'1','2018-02-26 11:47:24','2018-03-14 14:01:54',NULL),(34,3,1,1,'iM59tOXiyqHyjJraFH0hDA==','ADCcI4JDwbLnsf8UzlKRjQ==','QM93lfDuurNm5TNDrCw3/STp9lHk8EFW0DgmbuXv3/8=','$2y$10$k8EMoFnI1PYZ8r2VRxuQxu8jplhBORcfZ1fLuCJzwROCzjsEUvfgO','sH2zHQPlmj2348uAuaTUEg==','',0,0,231,'',NULL,'GGnDl40W7cVg0upwjZ8ZEmQL93XqDnCBbMUxETRRv1RIJka03ZwugUJCcwf3HkYHuLTPvCqftAfBNqrWOFNtGqnSKbxIW9vs5AkM','1',0,'1','2018-03-14 14:15:59','2018-03-14 14:16:00',NULL),(35,3,1,1,'1T3+6Hl4Pe/ka5xSWnNuYg==','YKDyHFWSgCcF7ZP941j4pw==','JwIIPzqJ4GTGBwWYcP57W5fJC2ojlp5MMydP9e6b1ns=','$2y$10$svakxzE7MpeAcLDVvmlBmu.jzZQj55dh5Nwhmm2FR3vjkWJnkfqCy','ifksHmV+0ObHVLETZS5Q0g==','',0,0,231,'',NULL,'x3T05HUazQXzAu1eCoSg9EKonmCN6hPpt2lb8IHvYsaGArrw5IYWs0SBW7XKSXcDVrZjmpUTgSddliAdeSJFtHTfnlkgH6JjCBZF','1',0,'1','2018-03-14 14:21:28','2018-03-14 14:21:28',NULL),(36,3,1,1,'1T3+6Hl4Pe/ka5xSWnNuYg==','YKDyHFWSgCcF7ZP941j4pw==','Rm7vFY+TL9dXdUzBsbG+ji28GazHYjKzLiShnZFBiwT7trg8ixCusq/rswqBOEFm','$2y$10$VOAwJu4yj5xeA4.R8cBVJuXFyaNwiBgZnqynXVC/n9FIM8MLKl7Pe','afcQOsrCL4CR4qGWosG1pw==','',0,0,231,'',NULL,'1','1',0,'1','2018-03-14 14:21:50','2018-03-14 14:22:25',NULL),(37,3,1,1,'1T3+6Hl4Pe/ka5xSWnNuYg==','YKDyHFWSgCcF7ZP941j4pw==','ecJJPuUUtGRP22RNN98gRBxbYqo5GkrX6wWNdmH/O8vhcm/i1nfLs8AF5moO11QU','$2y$10$V7ZR.wgtfD/u0Usv54wMOO2PmUGMvT.Ky7XStvm/KHLntbBl/MYPe','x6yBP6UOPwxIEIXFX/5Sqg==','',0,0,231,'',NULL,'qPtmeb3a9GSVu46gVWEAld0pkmVtArCDD3pLCl0wJYPfE4Aeu11Do6vb0BJCXjVzG7BGbkXlMPTXea1WmNDK8tuf1evuR3pGDz8m','1',0,'1','2018-03-14 14:26:09','2018-03-14 14:26:09',NULL),(38,3,1,1,'1T3+6Hl4Pe/ka5xSWnNuYg==','YKDyHFWSgCcF7ZP941j4pw==','OsiajEiadxxLTtxmeX/tYpR4Uq0feEK4xdjCpV7u44+BoLpAXCrEe1mYOETKYfun','$2y$10$UUX9cFjep1gfaXX2OGESlOxYrJOR6xMlX1Kxj4D0ckq0bPOf93xVy','yp5cNdtoNaAFXrOnD8k+EA==','',0,0,231,'',NULL,'1','1',0,'1','2018-03-14 14:27:54','2018-03-14 14:28:20',NULL),(39,3,1,1,'1T3+6Hl4Pe/ka5xSWnNuYg==','YKDyHFWSgCcF7ZP941j4pw==','fHf3eiLI9oUegJVezryb02sEvjiYzUY15bfTSCdBYqM+RX4CuIN8fehLDDVxGTBh','$2y$10$HvGqjN4ptGXHh3CofnVLBeAe.2XDx3PqMiRqR6BZzGUXWZpy7s5iS','AJJVze3PdFQolOkHXRouEw==','',0,0,231,'',NULL,'1','1',1,'1','2018-03-14 14:32:38','2018-03-14 14:32:48',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

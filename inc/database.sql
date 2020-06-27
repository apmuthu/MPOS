-- 1. Create a latin1 database that is collated with latin1_general_ci for English or utf8 database with utf8_unicode_ci!
-- 2. Execute the following SQL statements in succession in this database
-- 3. Default Admin account is "admin@example.com" with password as "password" - commented out
-- 4. Register custom user at register.php

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `passwort` varchar(255) NOT NULL,
  `vorname` varchar(255) NOT NULL,
  `nachname` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `passwortcode` varchar(255) DEFAULT NULL,
  `passwortcode_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`), 
  UNIQUE (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `securitytokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(10) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `securitytoken` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `change_price` double NOT NULL,
  `balance` double NOT NULL,
  `updated_by` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `barcode` int(10) unsigned NOT NULL,
  `price` double NOT NULL,
  `quantity` int(10) unsigned DEFAULT NULL,
  `color` VARCHAR( 7 ) NOT NULL,
  PRIMARY KEY (`id`), UNIQUE (`barcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE IF NOT EXISTS `company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `number` int(10) unsigned NOT NULL,
  `postcode` int(10) unsigned NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tel` int(10) unsigned NOT NULL,
  `logo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`), UNIQUE (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `MPOS`.`company` (`id`, `name`, `street`, `number`, `postcode`, `city`, `state`, `email`, `tel`, `logo`) VALUES (NULL, 'Name', 'Street', '0', '12345', 'City', 'Singapore', 'contact@example.com', '1234567890', 'logo/logo.svg');

-- INSERT INTO `users` (`id`, `email`, `passwort`, `vorname`, `nachname`, `created_at`, `updated_at`, `passwortcode`, `passwortcode_time`) VALUES (NULL,'admin@example.com','$2y$10$Lao5kA4bLUeNZIOC0HSK2.z8v4lUL3tYQ0Uq6f0YUH1w2UF0JkNVm','System','Administrator','2020-06-26 22:06:59',NULL,NULL,NULL);


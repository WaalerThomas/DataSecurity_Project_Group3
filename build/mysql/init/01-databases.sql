-- Create Database for steg2
CREATE DATABASE IF NOT EXISTS `datasec_db_2`;
USE `datasec_db_2`;

-- -----------------------------------------------------
-- Table `comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `comments` (
  `idcomments` INT NOT NULL AUTO_INCREMENT,
  `comment` VARCHAR(255) NULL,
  `messages_idmessages` INT NOT NULL,
  PRIMARY KEY (`idcomments`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- -----------------------------------------------------
-- Table `courses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `courses` (
  `idcourses` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `pin` INT NOT NULL,
  `users_iduser` INT NOT NULL,
  PRIMARY KEY (`idcourses`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- -----------------------------------------------------
-- Table `messages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
  `idmessages` INT NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(255) NULL,
  `answer` VARCHAR(255) NULL,
  `courses_idcourses` INT NOT NULL,
  `users_iduser` INT NOT NULL,
  PRIMARY KEY (`idmessages`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- -----------------------------------------------------
-- Table `password_reset_temp`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_reset_temp` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(250) NOT NULL,
  `key` VARCHAR(250) NOT NULL,
  `expDate` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- -----------------------------------------------------
-- Table `api_keys`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_keys` (
  `api_key` VARCHAR(64) NOT NULL,
  `auth_key` VARCHAR(64) NOT NULL,
  `expDate` DATETIME NOT NULL,
  PRIMARY KEY (`api_key`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- -----------------------------------------------------
-- Table `api_sessions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_sessions` (
  `session_id` VARCHAR(64) NOT NULL,
  `users_iduser` INT NOT NULL,
  PRIMARY KEY (`session_id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- -----------------------------------------------------
-- Table `reports`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reports` (
  `idreports` INT NOT NULL AUTO_INCREMENT,
  `messages_idmessages` INT NOT NULL,
  PRIMARY KEY (`idreports`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- -----------------------------------------------------
-- Table `user_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_type` (
  `iduser_type` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`iduser_type`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `iduser` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `picture` VARCHAR(255) NULL,
  `user_type_iduser_type` INT NOT NULL,
  PRIMARY KEY (`iduser`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- -----------------------------------------------------
-- Table `ip`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ip` (
  `address` char(16) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP)
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- -----------------------------------------------------
-- Data for table `user_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `datasec_db_2`;
INSERT INTO `user_type` (`iduser_type`, `name`) VALUES (1, 'student');
INSERT INTO `user_type` (`iduser_type`, `name`) VALUES (2, 'lecturer');
INSERT INTO `user_type` (`iduser_type`, `name`) VALUES (3, 'admin');

COMMIT;

-- -----------------------------------------------------
-- Data for table `users`
-- -----------------------------------------------------
START TRANSACTION;
USE `datasec_db_2`;
INSERT INTO `users` (`first_name`, `last_name`, `password`, `email`, `picture`, `user_type_iduser_type`)
VALUES ('Admin', 'Admin', '$2y$10$QuEY/hEkZ5nvlE2Zvc.MReB.uxHehvh6vNmUNCSYROPWjSeRHQc1.', 'admin@company.no', NULL, (
  SELECT `iduser_type`
  FROM `user_type`
  WHERE `name` = 'admin'
));

COMMIT;

-- Create Users and such
-- TODO: Make better passwords for the users
-- CREATE USER 'datasec_db_2_master'@'localhost' IDENTIFIED BY 'master';
CREATE USER 'datasec_db_2_guest'@'%' IDENTIFIED BY 'guestpassword';
-- CREATE USER 'datasec_db_2_student'@'localhost' IDENTIFIED BY 'student';
-- CREATE USER 'datasec_db_2_lecturer'@'localhost' IDENTIFIED BY 'lecturer';

-- ===== Do permissions for master
-- GRANT ALL ON `datasec_db_2`.*
-- TO 'datasec_db_2_master'@'localhost'
-- WITH GRANT OPTION;

-- ===== Do permissions for guest
GRANT SELECT, INSERT ON `datasec_db_2`.*
TO 'datasec_db_2_guest'@'%';

GRANT UPDATE ON `datasec_db_2`.`messages`
TO 'datasec_db_2_guest'@'%';

GRANT UPDATE ON `datasec_db_2`.`users`
TO 'datasec_db_2_guest'@'%';

GRANT DELETE ON `datasec_db_2`.`password_reset_temp`
TO 'datasec_db_2_guest'@'%';

GRANT DELETE ON `datasec_db_2`.`ip`
TO 'datasec_db_2_guest'@'%';

-- GRANT INSERT ON `datasec_db_2`.`reports`
-- TO 'datasec_db_2_guest'@'%';

-- Hide which user has written the comment
-- GRANT SELECT(`idmessages`,`question`, `answer`, `courses_idcourses`), INSERT
-- ON `datasec_db_2`.`messages`
-- TO 'datasec_db_2_guest'@'%';

-- GRANT SELECT, INSERT ON `datasec_db_2`.`comments`
-- TO 'datasec_db_2_guest'@'%';

-- GRANT SELECT, INSERT ON `datasec_db_2`.`courses`
-- TO 'datasec_db_2_guest'@'%';

-- GRANT SELECT, INSERT ON `datasec_db_2`.`users`
-- TO 'datasec_db_2_guest'@'%';

-- GRANT SELECT ON `datasec_db_2`.`user_type`
-- TO 'datasec_db_2_guest'@'%';

-- NOTE: Granting these could open up for steeling session tokens
-- GRANT SELECT, INSERT ON `datasec_db_2`.`api_sessions`
-- TO 'datasec_db_2_guest'@'localhost';
-- GRANT SELECT, INSERT ON `datasec_db_2`.`api_keys`
-- TO 'datasec_db_2_guest'@'localhost';

-- ===== Do permissions for student


-- ===== Do permissions for lecturer


-- ===== Lock down the root user
-- DROP USER 'root'@'%';

-- REVOKE ALL ON *.* FROM 'root'@'localhost';

-- GRANT ALL ON mysql.*
-- TO 'root'@'localhost'
-- WITH GRANT OPTION;

-- GRANT ALL ON sys.*
-- TO 'root'@'localhost'
-- WITH GRANT OPTION;

FLUSH PRIVILEGES;
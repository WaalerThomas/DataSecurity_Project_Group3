-- MySQL Script generated by MySQL Workbench
-- Mon Feb  5 15:05:13 2024
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema datasec_db
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `datasec_db` ;

-- -----------------------------------------------------
-- Schema datasec_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `datasec_db` DEFAULT CHARACTER SET utf8 ;
SHOW WARNINGS;
USE `datasec_db` ;

-- -----------------------------------------------------
-- Table `comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `comments` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `comments` (
  `idcomments` INT NOT NULL AUTO_INCREMENT,
  `comment` VARCHAR(255) NULL,
  `messages_idmessages` INT NOT NULL,
  PRIMARY KEY (`idcomments`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `courses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `courses` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `courses` (
  `idcourses` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `pin` INT NOT NULL,
  `users_iduser` INT NOT NULL,
  PRIMARY KEY (`idcourses`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `messages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `messages` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `messages` (
  `idmessages` INT NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(255) NULL,
  `answer` VARCHAR(255) NULL,
  `courses_idcourses` INT NOT NULL,
  `users_iduser` INT NOT NULL,
  PRIMARY KEY (`idmessages`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `password_reset_temp`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `password_reset_temp` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `password_reset_temp` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(250) NOT NULL,
  `key` VARCHAR(250) NOT NULL,
  `expDate` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `api_keys`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `api_keys` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `api_keys` (
  `api_key` VARCHAR(64) NOT NULL,
  `auth_key` VARCHAR(64) NOT NULL,
  `expDate` DATETIME NOT NULL,
  PRIMARY KEY (`api_key`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `api_sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `api_sessions` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `api_sessions` (
  `session_id` VARCHAR(64) NOT NULL,
  `users_iduser` INT NOT NULL,
  PRIMARY KEY (`session_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `reports`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reports` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `reports` (
  `idreports` INT NOT NULL AUTO_INCREMENT,
  `messages_idmessages` INT NOT NULL,
  PRIMARY KEY (`idreports`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `user_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_type` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `user_type` (
  `iduser_type` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`iduser_type`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `users` (
  `iduser` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `picture` VARCHAR(255) NULL,
  `user_type_iduser_type` INT NOT NULL,
  PRIMARY KEY (`iduser`))
ENGINE = InnoDB;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `user_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `datasec_db`;
INSERT INTO `user_type` (`iduser_type`, `name`) VALUES (1, 'student');
INSERT INTO `user_type` (`iduser_type`, `name`) VALUES (2, 'lecturer');
INSERT INTO `user_type` (`iduser_type`, `name`) VALUES (3, 'admin');

COMMIT;

-- -----------------------------------------------------
-- Data for table `users`
-- -----------------------------------------------------
START TRANSACTION;
USE `datasec_db`;
INSERT INTO `users` (`first_name`, `last_name`, `password`, `email`, `picture`, `user_type_iduser_type`)
VALUES ('Admin', 'Admin', '$2y$10$QuEY/hEkZ5nvlE2Zvc.MReB.uxHehvh6vNmUNCSYROPWjSeRHQc1.', 'admin@company.no', NULL, (
  SELECT `iduser_type`
  FROM `user_type`
  WHERE `name` = 'admin'
));

COMMIT;
-- MySQL Workbench Forward Engineering

SET NAMES utf8mb4;

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema pcr
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema pcr
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `pcr` DEFAULT CHARACTER SET utf8mb4 ;
USE `pcr` ;

-- -----------------------------------------------------
-- Table `pcr`.`grades`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`grades` (
  `grade_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `grade_name` VARCHAR(10) NOT NULL,
  `min_spend` INT UNSIGNED NOT NULL,
  `discount_rate` INT UNSIGNED NOT NULL CHECK (discount_rate <= 100),
  PRIMARY KEY (`grade_id`),
  UNIQUE INDEX `grade_id_UNIQUE` (`grade_id` ASC) VISIBLE,
  UNIQUE INDEX `grade_name_UNIQUE` (`grade_name` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `pcr`.`members`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`members` (
  `member_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_name` VARCHAR(30) NOT NULL,
  `login_id` VARCHAR(12) NOT NULL,
  `phone` VARCHAR(15) NOT NULL,
  `email` VARCHAR(50) NULL DEFAULT NULL,
  `remain_time` INT UNSIGNED NOT NULL DEFAULT 0,
  `total_spent` INT UNSIGNED NOT NULL DEFAULT 0,
  `grade_id` INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`member_id`),
  UNIQUE INDEX `member_id_UNIQUE` (`member_id` ASC) VISIBLE,
  UNIQUE INDEX `login_id_UNIQUE` (`login_id` ASC) VISIBLE,
  UNIQUE INDEX `phone_UNIQUE` (`phone` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  INDEX `fk_grade_id_idx` (`grade_id` ASC) VISIBLE,
  CONSTRAINT `fk_grade_id_mem`
    FOREIGN KEY (`grade_id`)
    REFERENCES `pcr`.`grades` (`grade_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `pcr`.`orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`orders` (
  `order_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `member_id` INT UNSIGNED NOT NULL,
  `grade_id_at_order` INT UNSIGNED NOT NULL,
  `discount_rate_at_order` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE INDEX `order_id_UNIQUE` (`order_id` ASC) VISIBLE,
  INDEX `fk_grade_id_idx` (`grade_id_at_order` ASC) VISIBLE,
  INDEX `fk_member_id_order_idx` (`member_id` ASC) VISIBLE,
  CONSTRAINT `fk_grade_id_or`
    FOREIGN KEY (`grade_id_at_order`)
    REFERENCES `pcr`.`grades` (`grade_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_member_id`
    FOREIGN KEY (`member_id`)
    REFERENCES `pcr`.`members` (`member_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `pcr`.`products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`products` (
  `product_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_type` ENUM('TIME', 'FOOD') NOT NULL,
  `product_name` VARCHAR(20) NOT NULL,
  `current_price` INT UNSIGNED NOT NULL CHECK (current_price > 0),
  `status` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`product_id`),
  UNIQUE INDEX `product_id_UNIQUE` (`product_id` ASC) VISIBLE,
  UNIQUE INDEX `product_name_UNIQUE` (`product_name` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `pcr`.`order_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`order_details` (
  `detail_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED NOT NULL,
  `price_at_sale` INT UNSIGNED NOT NULL,
  `quantity` INT UNSIGNED NOT NULL CHECK (quantity > 0),
  PRIMARY KEY (`detail_id`),
  UNIQUE INDEX `detail_id_UNIQUE` (`detail_id` ASC) VISIBLE,
  UNIQUE INDEX `UQ_order_product` (`order_id` ASC, `product_id` ASC) VISIBLE,
  INDEX `fk_order_id_idx` (`order_id` ASC) VISIBLE,
  INDEX `fk_product_id_od_idx` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_order_id`
    FOREIGN KEY (`order_id`)
    REFERENCES `pcr`.`orders` (`order_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_product_id_od`
    FOREIGN KEY (`product_id`)
    REFERENCES `pcr`.`products` (`product_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `pcr`.`price_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`price_history` (
  `history_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `old_price` INT UNSIGNED NOT NULL,
  `new_price` INT UNSIGNED NOT NULL,
  `change_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`history_id`),
  UNIQUE INDEX `history_id_UNIQUE` (`history_id` ASC) VISIBLE,
  INDEX `fk_product_id_ph_idx` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_product_id_ph`
    FOREIGN KEY (`product_id`)
    REFERENCES `pcr`.`products` (`product_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `pcr`.`product_logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`product_logs` (
  `log_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `change_stock` INT NOT NULL,
  `change_reason` ENUM('IN', 'SALE', 'DISCARD') NOT NULL,
  `record_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  UNIQUE INDEX `log_id_UNIQUE` (`log_id` ASC) VISIBLE,
  INDEX `fk_product_id_pl` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_product_id_pl`
    FOREIGN KEY (`product_id`)
    REFERENCES `pcr`.`products` (`product_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `pcr`.`products_food`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`products_food` (
  `product_id` INT UNSIGNED NOT NULL,
  `stock` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`product_id`),
  UNIQUE INDEX `product_id_UNIQUE` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_product_id_pf`
    FOREIGN KEY (`product_id`)
    REFERENCES `pcr`.`products` (`product_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `pcr`.`products_time`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`products_time` (
  `product_id` INT UNSIGNED NOT NULL,
  `time_value` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE INDEX `product_id_UNIQUE` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_product_id_pt`
    FOREIGN KEY (`product_id`)
    REFERENCES `pcr`.`products` (`product_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `pcr`.`daily_sales`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`daily_sales` (
  `date` DATE NOT NULL,
  `total_sales` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`date`))
ENGINE = InnoDB;

USE `pcr` ;

-- -----------------------------------------------------
-- Placeholder table for view `pcr`.`v_product_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`v_product_details` (`'상품 ID'` INT, `'상품 이름'` INT, `'상품 타입'` INT, `'현재 가격'` INT, `'시간(분)'` INT, `'재고'` INT);

-- -----------------------------------------------------
-- Placeholder table for view `pcr`.`v_member_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pcr`.`v_member_details` (`'회원 코드'` INT, `'회원 이름'` INT, `'회원 아이디'` INT, `'회원 등급'` INT, `'잔여 시간'` INT);

-- -----------------------------------------------------
-- View `pcr`.`v_product_details`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pcr`.`v_product_details`;
USE `pcr`;
CREATE  OR REPLACE VIEW `v_product_details` AS
SELECT
	p.product_id AS '상품 ID',
    p.product_name AS '상품 이름',
    p.product_type AS '상품 타입',
    p.current_price AS '현재 가격',
    pt.time_value AS '시간(분)',
    pf.stock AS '재고'
FROM
	products AS p
LEFT JOIN
	products_time AS pt ON p.product_id = pt.product_id
LEFT JOIN
	products_food AS pf ON p.product_id = pf.product_id;

-- -----------------------------------------------------
-- View `pcr`.`v_member_details`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pcr`.`v_member_details`;
USE `pcr`;
CREATE  OR REPLACE VIEW `v_member_details` AS
SELECT
	m.member_id AS '회원 코드',
    m.member_name AS '회원 이름',
    m.login_id AS '회원 아이디',
    g.grade_name AS '회원 등급',
    m.remain_time AS '잔여 시간'
FROM
	members AS m
JOIN
	grades AS g ON m.grade_id = g.grade_id;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

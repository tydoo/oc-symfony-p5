-- MySQL Script generated by MySQL Workbench
-- Wed Jan 24 14:44:38 2024
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;

SET
    @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS,
    FOREIGN_KEY_CHECKS = 0;

SET
    @OLD_SQL_MODE = @@SQL_MODE,
    SQL_MODE = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mon-blog
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mon-blog
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mon-blog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `mon-blog`;

-- -----------------------------------------------------
-- Table `mon-blog`.`level`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mon-blog`.`level` (
    `id` INT NOT NULL AUTO_INCREMENT, `name` VARCHAR(255) NOT NULL, `heritage` VARCHAR(255) NULL, PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mon-blog`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mon-blog`.`user` (
    `id` INT NOT NULL AUTO_INCREMENT, `firstname` VARCHAR(255) NOT NULL, `lastname` VARCHAR(255) NOT NULL, `email` VARCHAR(255) NOT NULL, `password` VARCHAR(60) NOT NULL, `created_at` DATETIME NOT NULL, `level_id` INT NOT NULL DEFAULT 1, PRIMARY KEY (`id`, `email`), INDEX `fk_level_idx` (`level_id` ASC), UNIQUE INDEX `email_UNIQUE` (`email` ASC), CONSTRAINT `fk_user_level` FOREIGN KEY (`level_id`) REFERENCES `mon-blog`.`level` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mon-blog`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mon-blog`.`category` (
    `id` INT NOT NULL AUTO_INCREMENT, `name` VARCHAR(255) NOT NULL, PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mon-blog`.`blog_post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mon-blog`.`blog_post` (
    `id` INT NOT NULL AUTO_INCREMENT, `title` VARCHAR(255) NOT NULL, `post` LONGTEXT NOT NULL, `chapo` VARCHAR(255) NOT NULL, `created_at` DATETIME NOT NULL, `updated_at` DATETIME NOT NULL, `user_id` INT NOT NULL, `category_id` INT NOT NULL, PRIMARY KEY (`id`), INDEX `fk_blog_post_user1_idx` (`user_id` ASC), INDEX `fk_blog_post_category1_idx` (`category_id` ASC), CONSTRAINT `fk_blog_post_user1` FOREIGN KEY (`user_id`) REFERENCES `mon-blog`.`user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, CONSTRAINT `fk_blog_post_category1` FOREIGN KEY (`category_id`) REFERENCES `mon-blog`.`category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mon-blog`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mon-blog`.`comment` (
    `id` INT NOT NULL AUTO_INCREMENT, `comment` LONGTEXT NOT NULL, `created_at` DATETIME NOT NULL, `validated` INT NOT NULL DEFAULT 0, `user_id` INT NOT NULL, `blog_post_id` INT NOT NULL, PRIMARY KEY (`id`), INDEX `fk_comment_user1_idx` (`user_id` ASC), INDEX `fk_comment_blog_post1_idx` (`blog_post_id` ASC), CONSTRAINT `fk_comment_user1` FOREIGN KEY (`user_id`) REFERENCES `mon-blog`.`user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, CONSTRAINT `fk_comment_blog_post1` FOREIGN KEY (`blog_post_id`) REFERENCES `mon-blog`.`blog_post` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE = InnoDB;

SET SQL_MODE = @OLD_SQL_MODE;

SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;

SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;

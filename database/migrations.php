<?php
/**
 * Build database structure
 */

$migrations = [];

$migrations['tests'] = [
    'up' => "CREATE TABLE `tests` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `uuid` VARCHAR(64) NOT NULL,
        `name` TEXT NOT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `published_at` DATETIME NULL,
        `deleted_at` DATETIME NULL,
        `sequence` INT NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`),
        UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC) VISIBLE);
      ",
    'down' => "DROP TABLE IF EXISTS `tests`;"
];

$migrations['respondents'] = [
    'up' => "CREATE TABLE `respondents` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `test_id` INT NOT NULL,
        `uuid` VARCHAR(64) NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `completed_at` DATETIME NULL,
        `deleted_at` DATETIME NULL,
        PRIMARY KEY (`id`),
        UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC) VISIBLE,
        INDEX `resp_test_id_fk_idx` (`test_id` ASC) VISIBLE,
        CONSTRAINT `resp_test_id_fk`
          FOREIGN KEY (`test_id`)
          REFERENCES `tests` (`id`)
          ON DELETE CASCADE
          ON UPDATE CASCADE);
      ", 
    'down' => "DROP TABLE IF EXISTS `respondents`;"
];

$migrations['questions'] = [
    'up' => "CREATE TABLE `questions` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `test_id` INT NOT NULL,
        `uuid` VARCHAR(64) NOT NULL,
        `description` TEXT NOT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `deleted_at` DATETIME NULL,
        `sequence` INT NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`),
        UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC) VISIBLE,
        INDEX `q_test_id_fk_idx` (`test_id` ASC) VISIBLE,
        CONSTRAINT `q_test_id_fk`
          FOREIGN KEY (`test_id`)
          REFERENCES `tests` (`id`)
          ON DELETE CASCADE
          ON UPDATE CASCADE);      
      ",
    'down' => "DROP TABLE IF EXISTS `questions`;"
];


$migrations['options'] = [
    'up' => "CREATE TABLE `options` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `question_id` INT NOT NULL,
        `uuid` VARCHAR(64) NOT NULL,
        `label` TEXT NOT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `deleted_at` DATETIME NULL,
        `sequence` INT NOT NULL DEFAULT 0,
        `is_correct_answer` TINYINT NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`),
        INDEX `opt_question_id_fk_idx` (`question_id` ASC) VISIBLE,
        CONSTRAINT `opt_question_id_fk`
        FOREIGN KEY (`question_id`)
        REFERENCES `questions` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE);
    ",
    'down' => "DROP TABLE IF EXISTS `options`;"
];

$migrations['answers'] = [
    'up' => "CREATE TABLE `answers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `respondent_id` INT NOT NULL,
  `question_id` INT NOT NULL,
  `option_id` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `answ_respondent_id_fk_idx` (`respondent_id` ASC) VISIBLE,
  INDEX `answ_question_id_fk_idx` (`question_id` ASC) VISIBLE,
  INDEX `answ_option_id_fk_idx` (`option_id` ASC) VISIBLE,
  CONSTRAINT `answ_respondent_id_fk`
    FOREIGN KEY (`respondent_id`)
    REFERENCES `respondents` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `answ_question_id_fk`
    FOREIGN KEY (`question_id`)
    REFERENCES `questions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `answ_option_id_fk`
    FOREIGN KEY (`option_id`)
    REFERENCES `options` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
    ",
    'down' =>  "DROP TABLE IF EXISTS `answers`;"
];

  
return $migrations;
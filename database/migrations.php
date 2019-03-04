<?php
/**
 * Build database structure
 */
//CREATE SCHEMA `test` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;



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
        UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC));
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
        UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC));
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
        UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC),
        INDEX `q_test_id_idx` (`test_id` ASC));      
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
        UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC),
        INDEX `opt_question_id_idx` (`question_id` ASC));
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
  INDEX `answ_respondent_id_idx` (`respondent_id` ASC),
  UNIQUE INDEX `resp_id_quest_id_uniq` (`respondent_id` ASC, `question_id` ASC));
    ",
    'down' =>  "DROP TABLE IF EXISTS `answers`;"
];

  
return $migrations;
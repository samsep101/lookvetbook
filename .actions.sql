CREATE TABLE `action` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`name` INT NULL,
	`image` VARCHAR(255) NULL,
	`info` TEXT NULL,
	`clinic_id` INT NULL,
	PRIMARY KEY (`id`),
	INDEX `clinic` (`clinic_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `action_to_specialization` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`action_id` INT NOT NULL,
	`specialization_id` INT NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

ALTER TABLE `action`
	ADD COLUMN `date_from` DATE NULL DEFAULT NULL AFTER `image`,
	ADD COLUMN `date_to` DATE NULL DEFAULT NULL AFTER `date_from`;
<?php

	$installer = $this;
	
	$installer->startSetup();
	
	$installer->run("
		-- -----------------------------------------------------
		-- Table `pixxy_csvimport_file`
		-- -----------------------------------------------------
		DROP TABLE IF EXISTS {$this->getTable('pixxy_csvimport_file')};
		CREATE TABLE {$this->getTable('pixxy_csvimport_file')} (
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `profile_id` INT NOT NULL,
		  `file_location` VARCHAR(255) NULL,
		  `filename_original` VARCHAR(45) NULL,
		  `count_rows` INT NULL DEFAULT 0,
		  `imported_rows` INT NULL DEFAULT 0,
		  `active` INT(1) NULL,
		  `ordinal_number` INT(11) NULL,
		  `errors_warnings` INT(11) DEFAULT 0,
		  `indexed` INT(1) NULL DEFAULT 0,
		  `date_uploaded` DATETIME NULL,
  		  `user` VARCHAR(45) NULL,
		  PRIMARY KEY (`id`),
		  INDEX `fk_pixxy_csvimport_file_pixxy_csvimport_mappingprofile1_idx` (`profile_id` ASC),
		  CONSTRAINT `fk_pixxy_csvimport_file_pixxy_csvimport_mappingprofile1`
		    FOREIGN KEY (`profile_id`)
		    REFERENCES `pixxy_csvimport_mappingprofile` (`id`)
		    ON DELETE CASCADE
		    ON UPDATE CASCADE)
		ENGINE = InnoDB;
			
		-- -----------------------------------------------------
		-- Table `pixxy_csvimport_mappingprofile`
		-- -----------------------------------------------------
		DROP TABLE IF EXISTS {$this->getTable('pixxy_csvimport_mappingprofile')};
		CREATE TABLE {$this->getTable('pixxy_csvimport_mappingprofile')} (
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `profile_name` VARCHAR(45) NOT NULL,
		  `mode` INT(1) NULL,
  		  `update_stock` INT(1) NULL,
		  PRIMARY KEY (`id`))
		ENGINE = InnoDB;
		
		-- -----------------------------------------------------
		-- Table `pixxy_csvimport_mappingattribute`
		-- -----------------------------------------------------
		DROP TABLE IF EXISTS {$this->getTable('pixxy_csvimport_mappingattribute')};
		CREATE TABLE {$this->getTable('pixxy_csvimport_mappingattribute')} (
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `mappingprofile_id` INT NOT NULL,
		  `mg_attribute_code` VARCHAR(45) NULL,
		  `mg_attribute_id` INT NOT NULL,
		  PRIMARY KEY (`id`),
		  INDEX `fk_csvimport_mappingattribute_mappingprofile_idx` (`mappingprofile_id` ASC),
		  CONSTRAINT `fk_csvimport_mappingattribute_mappingprofile`
		    FOREIGN KEY (`mappingprofile_id`)
		    REFERENCES {$this->getTable('pixxy_csvimport_mappingprofile')} (`id`)
		    ON DELETE CASCADE
		    ON UPDATE CASCADE)
		ENGINE = InnoDB;
		
		-- -----------------------------------------------------
		-- Table `pixxy_csvimport_log`
		-- -----------------------------------------------------
		DROP TABLE IF EXISTS {$this->getTable('pixxy_csvimport_log')};
		CREATE TABLE {$this->getTable('pixxy_csvimport_log')} (
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `file_id` INT NOT NULL,
		  `sku` VARCHAR(255) NULL,
		  `status` INT NULL,
		  `error` VARCHAR(255) NULL,
		  `full_error` TEXT NULL,
		  PRIMARY KEY (`id`),
		  INDEX `fk_pixxy_csvimport_log_pixxy_csvimport_file1_idx` (`file_id` ASC),
		  CONSTRAINT `fk_pixxy_csvimport_log_pixxy_csvimport_file1`
		    FOREIGN KEY (`file_id`)
		    REFERENCES {$this->getTable('pixxy_csvimport_file')} (`id`)
		    ON DELETE CASCADE
		    ON UPDATE CASCADE)
		ENGINE = InnoDB;
		
		-- -----------------------------------------------------
		-- Table `pixxy_csvimport_reindex`
		-- -----------------------------------------------------
		DROP TABLE IF EXISTS {$this->getTable('pixxy_csvimport_reindex')};
		CREATE TABLE {$this->getTable('pixxy_csvimport_reindex')} (
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `mappingprofile_id` INT NULL,
		  `index_code` VARCHAR(255) NULL,
		  PRIMARY KEY (`id`),
		  INDEX `fk_pixxy_csvimport_reindex_pixxy_csvimport_mappingprofile1_idx` (`mappingprofile_id` ASC),
		  CONSTRAINT `fk_pixxy_csvimport_reindex_pixxy_csvimport_mappingprofile1`
		    FOREIGN KEY (`mappingprofile_id`)
		    REFERENCES {$this->getTable('pixxy_csvimport_mappingprofile')} (`id`)
		    ON DELETE NO ACTION
		    ON UPDATE NO ACTION)
		ENGINE = InnoDB;
		
		-- -----------------------------------------------------
		-- Table `pixxy_csvimport_tmppositions`
		-- -----------------------------------------------------
		DROP TABLE IF EXISTS {$this->getTable('pixxy_csvimport_tmppositions')};
		CREATE TABLE IF NOT EXISTS {$this->getTable('pixxy_csvimport_tmppositions')} (
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `position` INT NOT NULL,
		  `mg_attribute` VARCHAR(255) NOT NULL,
		  PRIMARY KEY (`id`))
		ENGINE = InnoDB;
		
		-- -----------------------------------------------------
		-- Table `pixxy_csvimport_parent`
		-- -----------------------------------------------------
		DROP TABLE IF EXISTS {$this->getTable('pixxy_csvimport_parent')};
		CREATE TABLE IF NOT EXISTS {$this->getTable('pixxy_csvimport_parent')} (
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `file_id` VARCHAR(45) NULL,
		  `sku` VARCHAR(45) NULL,
		  `parent_sku` VARCHAR(45) NULL,
		  PRIMARY KEY (`id`))
		ENGINE = InnoDB;
		
	");
	
	$installer->endSetup();
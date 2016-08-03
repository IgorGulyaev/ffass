<?php

	$installer = $this;
	
	$installer->startSetup();
	
	$installer->run("
		ALTER TABLE {$this->getTable('pixxy_csvimport_mappingprofile')} 
		ADD COLUMN `hide` INT(1) NULL DEFAULT 0;
		
		ALTER TABLE {$this->getTable('pixxy_csvimport_mappingattribute')} 
		ADD COLUMN `alt_map` VARCHAR(45) NULL;
		
		-- -----------------------------------------------------
		-- Table `pixxy_csvimport_curproducts`
		-- -----------------------------------------------------
		DROP TABLE IF EXISTS {$this->getTable('pixxy_csvimport_curproducts')};
		CREATE TABLE IF NOT EXISTS {$this->getTable('pixxy_csvimport_curproducts')} (
		  `id` INT(11) NOT NULL AUTO_INCREMENT,
		  `file_id` VARCHAR(45) NULL,
		  `sku` VARCHAR(45) NULL,
		  `cur_product` VARCHAR(45) NULL,
		  `cur_product_type` VARCHAR(45) NULL,
		  PRIMARY KEY (`id`))
		ENGINE = InnoDB;
	");
	
	$installer->endSetup();
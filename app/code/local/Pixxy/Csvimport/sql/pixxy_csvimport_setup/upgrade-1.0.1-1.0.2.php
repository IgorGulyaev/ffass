<?php

	$installer = $this;
	
	$installer->startSetup();
	
	$installer->run("
		ALTER TABLE {$this->getTable('pixxy_csvimport_file')} 
		ADD COLUMN `date_import_start` DATETIME NULL DEFAULT NULL,
  		ADD COLUMN `date_import_end` DATETIME NULL DEFAULT NULL,
  		ADD COLUMN `delimiter` VARCHAR(5) NULL DEFAULT ',';
	  	
	  	ALTER TABLE {$this->getTable('pixxy_csvimport_mappingprofile')} 
	  	ADD COLUMN `filter_type_id` VARCHAR(255) NULL DEFAULT NULL,
	  	ADD COLUMN `filter_attribute_set_id` VARCHAR(255) NULL DEFAULT NULL;
	");
	
	$installer->endSetup();
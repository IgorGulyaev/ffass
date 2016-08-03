<?php

	$installer = $this;
	
	$installer->startSetup();
	
	$installer->run("
		ALTER TABLE {$this->getTable('pixxy_csvimport_file')} 
		ADD COLUMN `file_size` VARCHAR(255) NULL DEFAULT NULL;
	");
	
	$installer->endSetup();
	
	
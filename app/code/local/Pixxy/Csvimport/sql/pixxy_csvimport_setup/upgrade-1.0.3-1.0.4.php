<?php

	$installer = $this;
	
	$installer->startSetup();
	
	$installer->run("
		ALTER TABLE {$this->getTable('pixxy_csvimport_file')} 
		ADD COLUMN `last_ping` DATETIME NULL DEFAULT NULL,
		ADD COLUMN `indexer_mode` VARCHAR(255) NULL DEFAULT NULL;
	");
	
	$installer->endSetup();
	
	
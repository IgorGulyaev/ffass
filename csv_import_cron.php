<?php 

	chdir(dirname(__FILE__));
	
	require 'app/Mage.php';
	
	if (!Mage::isInstalled()) {
	    echo "Application is not installed yet, please complete install wizard first.";
	    exit;
	}
	
	// Only for urls
	// Don't remove this
	$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
	$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);
	
	Mage::app('admin')->setUseSessionInUrl(false);
	
	umask(0);

	if(file_exists(Mage::getBaseDir()."/var/csv_import.flag")){
		return;
	}
	else{
		$content = "Import in progress!";
		$fp = fopen(Mage::getBaseDir()."/var" . "/csv_import.flag","wb");
		fwrite($fp,$content);
		fclose($fp);
	}
	
	//first prepare
	Mage::getModel('pixxy_csvimport/file')->prepareNextFile();
	
	//second addnew/update 
	Mage::getModel('pixxy_csvimport/import')->prepareData();						
	
	//at end do reindex
	Mage::getModel('pixxy_csvimport/reindex')->doReindex();
	
	if(file_exists(Mage::getBaseDir()."/var/csv_import.flag")){
		unlink(Mage::getBaseDir()."/var/csv_import.flag");
	}
	

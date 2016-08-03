<?php

	class Pixxy_Csvimport_Adminhtml_TestController extends Mage_Adminhtml_Controller_Action{
		
		public function indexAction(){

			if(file_exists(Mage::getBaseDir()."/var/csv_import.flag")){
				echo "Import in progress!";
				return;
			}
			else{
				$content = "flag";
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
			
			//scanner
			Mage::getModel('pixxy_csvimport/scanner')->scan();
			
			if(file_exists(Mage::getBaseDir()."/var/csv_import.flag")){
				unlink(Mage::getBaseDir()."/var/csv_import.flag");
			}
			
			$this->loadLayout();
			$this->renderLayout();
		}
		
	}
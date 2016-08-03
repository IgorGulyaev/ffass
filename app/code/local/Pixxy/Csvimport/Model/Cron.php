<?php

	class Pixxy_Csvimport_Model_Cron{
		
		public function csv_import(){
			Mage::getModel('pixxy_csvimport/file')->prepareNextFile();
			Mage::getModel('pixxy_csvimport/import')->prepareData();						
		}
		
		public function csv_reindex(){
			Mage::getModel('pixxy_csvimport/reindex')->doReindex();
		}
		
		public function csv_scan(){
			Mage::getModel('pixxy_csvimport/scanner')->scan();
		}
		
	}
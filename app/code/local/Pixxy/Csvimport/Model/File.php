<?php

	class Pixxy_Csvimport_Model_File extends Mage_Core_Model_Abstract{
		
		protected $_helper;
		protected $_extensionEnabled;
		protected $_fileLocation;
		
		public function _construct(){
			$this->_init('pixxy_csvimport/file');
			
			$this->_helper = Mage::helper('csvimport');
			$this->_extensionEnabled = $this->_helper->getConfig('general/enabled');
			$this->_fileLocation = Mage::getBaseDir().$this->_helper->getConfig('general/file_location');
		}

		/**
		 * Returns last file
		 */
		public function getLastFile(){
			$lastFile = $this->getCollection()->getLastItem();
			if($lastFile && $lastFile->getId()){
				return $lastFile;
			}
		}
		
		/**
		 * Returns next file location for prepare import 
		 */
		public function getNextFileLocation(){
			$file = $this->getCollection()->addFieldToFilter('active', array('eq'=>'0'))->addFieldToFilter('imported_rows', array('eq'=>'0'))->setOrder('id', 'asc')->getFirstItem();
			if($file && $file->getId()){
				return $file;
			}
		}
		
		/**
		 * Check if there is any active import 
		 */
		public function importInProgress(){
			$file = $this->getCollection()->addFieldToFilter('active', array('eq'=>'1'))->getFirstItem();
			if($file && $file->getId()){
				return true;
			}
			return false;
		}
		
		/**
		 * Load all attributes from csv file 
		 */
		public function loadAttributesFromCsvFile($file){
			if(!file_exists($file->getFileLocation())){
				//echo 'file dont exist';
				return;
			}
			$file_attributes = array();
			ini_set('auto_detect_line_endings',TRUE);
			$handle = fopen($file->getFileLocation(),'r');
			$count = 0;
			while (($data = fgetcsv($handle, 0, $file->getDelimiter())) !== FALSE && $count < 1) {
				$file_attributes[] = $data;
				$count++;
			}
			fclose($handle);
			ini_set('auto_detect_line_endings',FALSE);
			return $file_attributes[0];
		}
		
		/**
		 * Prepare next file for import 
		 */
		public function prepareNextFile(){
			if(!$this->_extensionEnabled){
				$this->_helper->log("Extension disabled");
				return;
			}
			
			if($this->importInProgress()){
				//echo "Import in progress";
				return;	
			}
			
			if(!$this->getNextFileLocation()){
				//echo "next file location dont exist";
				return;
			}
			
			//if file don't exist
			if(!file_exists($this->getNextFileLocation()->getFileLocation())){
				$this->_helper->log("Error, file don't exist");
				return;
			}
			
			//load data from file
			$csv_import = array();
			ini_set('auto_detect_line_endings',TRUE);
			$handle = fopen($this->getNextFileLocation()->getFileLocation(),'r');
			while ( ($data = fgetcsv($handle, 0 , $this->getNextFileLocation()->getDelimiter()) ) !== FALSE ) {
				$csv_import[] = $data;
			}
			fclose($handle);
			ini_set('auto_detect_line_endings',FALSE);
			//end load data from file
			
			//set file active, num of rows, tmp_positions
			if(count($csv_import)>1){
				$file = $this->load($this->getNextFileLocation()->getId());
				$file->setCountRows(count($csv_import)-1);
				$file->setActive('1');
				$file->setDateImportStart(Mage::getModel('core/date')->gmtDate());
				//$file->save();
				
				//set indexers to manual mode
				$indexCollection = Mage::getModel('index/process')->getCollection();
				$indexers = array();
				foreach ($indexCollection as $index){
					$indexers[$index->getId()] = $index->getMode();
					$index->setMode('manual');
					$index->save();
				}
				$file->setIndexerMode(json_encode($indexers));
				$file->save();
				
				$this->_helper->log("Import started");
				$this->_helper->log("File active ".$file->getFileLocation());
				
				//insert tmp positions
				$mapped_attributes = Mage::getModel('pixxy_csvimport/mappingattribute')->getMappedAttributes($file->getProfileId());
				$file_attributes = $this->loadAttributesFromCsvFile($file);
				$positions = array();
				$mg_attribute_array = array();
				foreach ($file_attributes as $key => $value){
					foreach ($mapped_attributes as $mapped_attribute){
						if($mapped_attribute->getAltMap() != null && !in_array($mapped_attribute->getMgAttributeCode(), $mg_attribute_array)){
							if($value == $mapped_attribute->getAltMap()){
								array_push($positions, $key);
								array_push($mg_attribute_array, $mapped_attribute->getMgAttributeCode());
							}
						}
					}
				}
				foreach ($file_attributes as $key => $value){
					foreach ($mapped_attributes as $mapped_attribute){
						if(!in_array($mapped_attribute->getMgAttributeCode(), $mg_attribute_array)){
							if($value == $mapped_attribute->getMgAttributeCode() || $value == $mapped_attribute->getAltMap()){
								array_push($positions, $key);
								array_push($mg_attribute_array, $mapped_attribute->getMgAttributeCode());
							}
						}
					}
				}
				Mage::getModel('pixxy_csvimport/tmppositions')->addNewPositions($positions, $mg_attribute_array);
			}
			else{
				$file = $this->load($this->getNextFileLocation()->getId());
				$file->setCountRows(count($csv_import)-1);
				$file->setActive('1');
				$file->setDateImportStart(Mage::getModel('core/date')->gmtDate());
				$file->save();
				$this->_helper->insertLog('FATAL ERROR', 'Empty file', 'function prepareData');
				//$file->setErrorsWarnings((int)$file->getErrorsWarnings()+1);
				$file->setCountRows('999999');
				$file->setImportedRows('999999');
				$file->setActive('0');
				$file->setDateImportEnd(Mage::getModel('core/date')->gmtDate());
				$file->setIndexed('1');
				$file->save();
				if($this->_helper->getConfig('cronsettings/auto_delete_imported_files')){
					if(file_exists($file->getFileLocation())){
						unlink($file->getFileLocation());
					}
				}
			}
			
		}
		
		public static function getDelimiterFromFile($file) {
		    $delimiter = false;
		    $line = '';
		    if($f = fopen($file, 'r')) {
		        $line = fgets($f); 
		        fclose($f);
		    }
		    if(strpos($line, '	') !== FALSE && strpos($line, ',') === FALSE && strpos($line, ';') === FALSE) {
		        $delimiter = '	';
		    } else if(strpos($line, ',') !== FALSE && strpos($line, '	') === FALSE && strpos($line, ';') === FALSE) {
		        $delimiter = ',';
		    } else {
		    	$delimiter = ';';
		    }
	    	return $delimiter;
		}
		
		public function isActive($id){
			$file = $this->load($id);
			if($file && $file->getId()){
				if($file->getActive()=='1'){
					return true;
				}
			}
			return false;
		}
		
		public function deleteFile($id){
			$file = $this->load($id);
			if($file && $file->getId()){
				if($file->getUser() == 'CSV Import FTP Scanner'){
					$mapping_profile = Mage::getModel('pixxy_csvimport/mappingprofile')->load($file->getProfileId());
					if($mapping_profile && $mapping_profile->getId()){
						$mapping_profile_name = $this->clean($mapping_profile->getProfileName());
						$scanner_file = $this->_fileLocation.$mapping_profile_name.'/'.$file->getFilenameOriginal();
						if(file_exists($scanner_file)){
							unlink($scanner_file);
						} 
					}
				}
				if(file_exists($file->getFileLocation())){
					unlink($file->getFileLocation());
				}
				$file->delete();
			}
		}
		
		function clean($string) {
	   		$string = str_replace(' ', '-', $string); 
	   		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	   		return $string;
		}
		
	}
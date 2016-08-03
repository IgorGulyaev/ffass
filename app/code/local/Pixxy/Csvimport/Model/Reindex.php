<?php

	class Pixxy_Csvimport_Model_Reindex extends Mage_Core_Model_Abstract{
		
		private $_helper;
		
		public function _construct(){
			$this->_init('pixxy_csvimport/reindex');
			$this->_helper = Mage::helper('csvimport');
		}

		/**
		 * Get all index codes from mapping profile and do reindex 
		 */
		public function getMappingProfileIndexes($mappingprofile_id){
			$indexCollection = $this->getCollection()->addFieldToFilter('mappingprofile_id', array('eq'=>(int)$mappingprofile_id));
			if(count($indexCollection)){
				foreach ($indexCollection as $index) {
					$this->_helper->log("Indexing ".$index->getIndexCode());
				    $index = Mage::getModel('index/indexer')->getProcessByCode($index->getIndexCode());
				    $index->reindexAll();
				}
			}
			else{
				$this->_helper->log("No index selected");
			}
		}
		
		/**
		 * Reindex data 
		 */
		public function doReindex(){
			if(!Mage::getModel('pixxy_csvimport/file')->importInProgress()){
				//$file = Mage::getModel('pixxy_csvimport/file')->getLastFile();
				$file = Mage::getModel('pixxy_csvimport/file')->getCollection()->addFieldToFilter('count_rows', array('gt'=>'0'))->addFieldToFilter('imported_rows', array('gt'=>'0'))->addFieldToFilter('active', array('eq'=>'0'))->setOrder('id', 'asc')->getLastItem();
				if($file && $file->getId() && $file->getIndexed()=='0' && (int)$file->getCountRows() > (int)$file->getErrorsWarnings()){
					try {
						$file->setIndexed('1');
						$file->save();
						$this->_helper->log("Reindex started");
						$this->getMappingProfileIndexes($file->getProfileId());
						$this->_helper->log("Reindex comleted");
					} catch (Exception $e) {
						if(file_exists(Mage::getBaseDir()."/var/csv_import.flag")){
							unlink(Mage::getBaseDir()."/var/csv_import.flag");
						}
					}
				}
			}
		}
		
	}
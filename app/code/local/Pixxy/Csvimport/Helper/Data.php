<?php

	class Pixxy_Csvimport_Helper_Data extends Mage_Core_Helper_Abstract{
		
		public function getAllMagentoAttributes(){
			$attributes = Mage::getResourceModel('catalog/product_attribute_collection')->getItems();
			return $attributes;
		}

		public function getConfig($path){
			return Mage::getStoreConfig('csvimport/'.$path, Mage::app()->getStore()->getId());		
		}

		public function insertLog($sku, $error, $full_error){
			$read = Mage::getSingleton('core/resource')->getConnection('core_read');
			$file_table = Mage::getSingleton( 'core/resource' )->getTableName('pixxy_csvimport_file');
			$query = "SELECT id FROM {$file_table} WHERE active = 1";
			$result = $read->fetchRow($query);
			if(!$result['id']){
				return;
			}
			$file_id = (int)$result['id'];
			
			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
			$log_table = Mage::getSingleton('core/resource')->getTableName('pixxy_csvimport_log');
			$query = "INSERT INTO {$log_table} (file_id, sku, error, full_error) VALUES (:file_id, :sku, :error, :full_error)";
			$binds = array(
				'file_id' => $file_id,
				'sku' => $sku,
				'error' => $error,
				'full_error' => $full_error,
			);
			$write->query($query, $binds);
		}
		
		public function log($message){
			if($this->getConfig('debug/log')){
				Mage::log($message, null, $file = 'csv_import.log', true);
			}
		}
		
		public function scannerLog($message){
			if($this->getConfig('debug/log')){
				Mage::log($message, null, $file = 'csv_import_scanner.log', true);
			}
		}
		
	}
<?php

	class Pixxy_Csvimport_Model_Resource_Parent_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{
		
		private $_helper;
		
		public function _construct(){
			$this->_init('pixxy_csvimport/parent');
			$this->_helper = Mage::helper('csvimport');
		}
		
		public function processParents(){
			$parents = $this->setPageSize($this->_helper->getConfig('cronsettings/count_import'));
			if(count($parents)){
				foreach ($parents as $parent) {
					$child_sku = $parent->getSku();
					$parent_sku = $parent->getParentSku();
					$this->_helper->log("Processing parent id ".$parent->getId().", child sku ".$child_sku.", parent_sku ".$parent_sku);
					try {
						$parent->delete();
						$configurable = Mage::getModel('catalog/product')->loadByAttribute('sku', $parent_sku);
						if($configurable && $configurable->getId()){
							$simple = Mage::getModel('catalog/product')->loadByAttribute('sku', $child_sku);
							if($simple && $simple->getId()){
								$ids = $configurable->getTypeInstance()->getUsedProductIds();
								$newids = array();
								foreach ($ids as $id) {
									$newids[$id] = 1;
								}
								$newids[$simple->getId()] = 1;
								Mage::getResourceModel('catalog/product_type_configurable')->saveProducts($configurable, array_keys($newids));
								//return true;
							}
							else {
								//$this->_helper->log("ERROR while processing parent id ".$parent->getId()."(There isn\'t simple product ".$child_sku.")");
								$this->_helper->insertLog($parent_sku, "Error while processing parent id ".$parent->getId()."(There isn\'t simple product ".$child_sku.")", 'function processParents');
							}
						}
						else{
							//$this->_helper->log("ERROR while processing parent id ".$parent->getId()."(There isn\'t configurable product ".$parent_sku.")");
							$this->_helper->insertLog($parent_sku, "Error while processing parent id ".$parent->getId()."(There isn\'t configurable product ".$parent_sku.")", 'function processParents');
						}
					} catch (Exception $e) {
						//$this->_helper->log("ERROR while processing parent sku ".$parent_sku.". Message: ".$e->getMessage());
						$this->_helper->insertLog($parent_sku, "Error while processing parent sku ".$parent_sku.". Message: ".$e->getMessage(), $e);
					}
				}
				return true;
			}
			return false;
		}
		
	}
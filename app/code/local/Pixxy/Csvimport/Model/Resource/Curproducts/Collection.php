<?php

	class Pixxy_Csvimport_Model_Resource_Curproducts_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{
		
		private $_helper;
		
		public function _construct(){
			$this->_init('pixxy_csvimport/curproducts');
			$this->_helper = Mage::helper('csvimport');
		}
		
		public function processCurProducts(){			
			$cur_products = $this->setPageSize($this->_helper->getConfig('cronsettings/count_import'));
			if(count($cur_products)){
				foreach ($cur_products as $cur){
					$sku = $cur->getSku();
					$cur_product = $cur->getCurProduct();
					$cur_product_type = $cur->getCurProductType();
					try {
						$cur->delete();
						$product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
						$cur_product_model = Mage::getModel('catalog/product')->loadByAttribute('sku', $cur_product);
						if($cur_product_model && $cur_product_model->getId()){
							if($cur_product_type == 'cross_sells_skus'){
								$this->addCrossSellProduct($product, $cur_product_model);
							}
							else if($cur_product_type == 'up_sells_skus'){
								$this->addUpSellProduct($product, $cur_product_model);
							}
							else{
								$this->addRelatedProduct($product, $cur_product_model);
							}
							$product->save();
						}
						else{
							$this->_helper->insertLog($sku, "Error while processing ".$cur_product_type." (Product ".$cur_product." don\'t exist)", 'function processCurProducts');
						}
					} catch (Exception $e) {
						$this->_helper->insertLog($sku, "Error while processing cur product sku ".$sku.". Message: ".$e->getMessage(), $e);
					}
				}
				return true;
			}
			return false;
		}
		
		public function addCrossSellProduct($product, $cross_sell){
			$param = array();
			if($product->getCrossSellProducts()){
				foreach ($product->getCrossSellProducts() as $old_cross_sell){
					$param[$old_cross_sell->getId()] = '1';	
				}
			}
			$param[$cross_sell->getId()] = '1';	
			$product->setCrossSellLinkData($param);
		}
		
		public function addUpSellProduct($product, $up_sell){
			$param = array();
			if($product->getUpSellProductCollection()){
				foreach ($product->getUpSellProductCollection() as $old_up_sell){
					$param[$old_up_sell->getId()] = '1';	
				}
			}
			$param[$up_sell->getId()] = '1';	
			$product->setUpSellLinkData($param);
		}
		
		public function addRelatedProduct($product, $related_product){
			$param = array();
			if($product->getRelatedProductCollection()){
				foreach ($product->getRelatedProductCollection() as $old_related){
					$param[$old_related->getId()] = '1';	
				}
			}
			$param[$related_product->getId()] = '1';	
			$product->setRelatedLinkData($param);
		}

	}
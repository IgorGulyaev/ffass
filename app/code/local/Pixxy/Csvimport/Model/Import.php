<?php

	class Pixxy_Csvimport_Model_Import extends Mage_Core_Model_Abstract{
		
		private $_helper;
		private $_count_per_interval;
		
		private $_process_images;
		private $_image_fields;
		private $_selecting_image_fields;
		private $_process_image_labels;
		private $_image_label_fields;
		
		private $_required;
		private $_product;
		
		private $_filter_type_id_enabled;
		private $_filter_type_id;
		private $_filter_attribute_set_id_enabled;
		private $_filter_attribute_set_id;
		private $_error_codes;
		
		public function getCacheKey(){}
		public function getCacheLifetime(){}
		public function getCacheTags(){}
		
		public function _construct(){
			$this->_helper = Mage::helper('csvimport');
			$this->_count_per_interval = $this->_helper->getConfig('cronsettings/count_import');
			$this->_product = null;
			$this->_process_images = false;
			$this->_process_image_labels = false;
			$this->_image_fields = Pixxy_Csvimport_Model_Image::getImageFields();
			$this->_selecting_image_fields = Pixxy_Csvimport_Model_Image::getSelectingImageFields();
			$this->_image_label_fields = Pixxy_Csvimport_Model_Image::getImageLabels();
			$this->_required = array(
				//required fields by default for create new product
				'name', 
				'weight', 
				'status', 
				'tax_class_id', 
				'visibility', 
				'price', 
				'qty', 
				'description', 
				'short_description',
			);
			$this->_filter_type_id_enabled = false;
			$this->_filter_attribute_set_id_enabled = false;
			$this->_filter_type_id = array();
			$this->_filter_attribute_set_id = array();
			
			$this->_error_codes = array("#DIV/0!", "#N/A!", "#NAME?", "#NULL!", "#NUM!", "#REF!", "#VALUE!");
		}
		
		/**
		 * returns active file 
		 */
		public function getActiveFile(){
			return Mage::getModel('pixxy_csvimport/file')->getCollection()->addFieldToFilter('active', array('eq'=>'1'))->getFirstItem();
		}
		
		/**
		 * return profile for active file 
		 */
		public function getActiveFileProfile(){
			$profile_id = $this->getActiveFile()->getProfileId();
			return Mage::getModel('pixxy_csvimport/mappingprofile')->load($profile_id);
		}
		
		/**
		 * Load product by sku
		 */
		public function loadProductBySku($sku){
			$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
			if($product && $product->getId()){
				$this->_product = Mage::getModel('catalog/product')->load($product->getId());
				return;
			}
			$this->_product = null;
		}
		
		/**
		 *	Prepare data for import 
		 */
		public function prepareData(){
			$file = $this->getActiveFile();
			if($file && $file->getId()){
				$current_count = $file->getImportedRows();
				//there is no more rows to import
				if($current_count >= $file->getCountRows()){
					if($file->getActive()=='1'){
						//append child sku to configurable product
						if(Mage::getModel('pixxy_csvimport/parent')->getCollection()->processParents()){
							return;
						}
						if(Mage::getModel('pixxy_csvimport/curproducts')->getCollection()->processCurProducts()){
							return;
						}
						$file->setActive('0');
						$file->setDateImportEnd(Mage::getModel('core/date')->gmtDate());
						$file->save();
						$this->_helper->log("Import finished. ".$file->getFileLocation());
						if($this->_helper->getConfig('cronsettings/auto_delete_imported_files')){
							if(file_exists($file->getFileLocation())){
								unlink($file->getFileLocation());
							}
						}
						
						//return indexers
						$indexers = json_decode($file->getIndexerMode());
						foreach ($indexers as $key => $value){
							$indexer = Mage::getModel('index/process')->load($key);
							$indexer->setMode($value);
							$indexer->save();
						}
						
						$date1 = new DateTime($file->getDateImportStart());
						$date2 = new DateTime($file->getDateImportEnd());
						$interval = $date1->diff($date2);
						if($interval->d != '0'){
							$interval_diffrence = $interval->d. " days, ";	
						}
						else{
							$interval_diffrence = "";
						}
						$interval_diffrence .= $interval->h . " hours, " . $interval->i." minutes, ".$interval->s." seconds"; 
						$statistic = "
							<table border='1px'>
							  <tr>
							    <td>Number of rows</td>
							    <td>".$file->getCountRows()."</td>		
							  </tr>
							  <tr>
							    <td>Number of processed rows</td>
							    <td>".$file->getImportedRows()."</td>		
							  </tr>
							  <tr>
							    <td>Number of errors/warnings</td>
							    <td>".Mage::getModel('pixxy_csvimport/log')->getCollection()->addFieldToFilter('file_id', array('eq'=>$file->getId()))->getSize()."</td>		
							  </tr>
							  <tr>
							    <td>Date uploaded</td>
							    <td>".Mage::app()->getLocale()->date($file->getDateUploaded())."</td>		
							  </tr>
							  <tr>
							    <td>Date import start</td>
							    <td>".Mage::app()->getLocale()->date($file->getDateImportStart())."</td>		
							  </tr>
							  <tr>
							    <td>Date import end</td>
							    <td>".Mage::app()->getLocale()->date($file->getDateImportEnd())."</td>	
							  </tr>
							  <tr>
							    <td>Duration</td>
							    <td>".$interval_diffrence."</td>	
							  </tr>
							  <tr>
							    <td>File uploaded by user</td>
							    <td>".$file->getUser()."</td>	
							  </tr>
							</table>
						";
						Mage::getModel('pixxy_csvimport/email')->send($file->getFilenameOriginal(), 'import/update finished.'."<br>".$statistic);
					}
					return;
				}
				//load data from file
				$csv_import = array();
				ini_set('auto_detect_line_endings',TRUE);
				$handle = fopen($file->getFileLocation(),'r');
				$count = 0;
				while ( ($data = fgetcsv($handle, 0, $file->getDelimiter()) ) !== FALSE ) {
					if($count > $current_count && $count <= (int)$this->_count_per_interval+$current_count){
						$csv_import[] = $data;
					}
					$count++;
				}
				fclose($handle);
				ini_set('auto_detect_line_endings',FALSE);
				//end load data from file
				
				//update imported rows
				if(count($csv_import)){
					$file->setImportedRows($current_count + count($csv_import));
					$file->setLastPing(Mage::getModel('core/date')->gmtDate());
					$file->save();
				}
				
				//get mapping profile
				$mapping_profile = Mage::getModel('pixxy_csvimport/mappingprofile')->getCollection()->addFieldToFilter('id', array('eq'=>$file->getProfileId()))->getFirstItem();
				//find qty position
				$qty_position = '';
				//find sku position
				$sku_position = '';
				//find category_ids position
				$category_ids_position = '';
				//find attribute_set_id position
				$attribute_set_id_position = '';
				//find type_id position
				$type_id_position = '';
				//find parent_sku position
				$parent_sku_position = '';
				//bridge_images_import
				$bridge_images_position = '';
				//cross, up sells and related products positions
				$cross_sells_position = '';
				$up_sells_positon = '';
				$related_products_position = '';
				$file_attributes = Mage::getModel('pixxy_csvimport/file')->loadAttributesFromCsvFile($file);
				$position = 0;
				foreach ($file_attributes as $attribute){
					if($attribute == 'sku'){
						$sku_position = $position;
					}
					if($attribute == 'qty'){
						$qty_position = $position;
					}
					if($attribute == 'category_ids'){
						$category_ids_position = $position;
					}
					//required attributes for new product
					if($attribute == 'attribute_set_id'){
						$attribute_set_id_position = $position;
					}
					if($attribute == 'type_id'){
						$type_id_position = $position;
					}
					if($attribute == 'parent_sku'){
						$parent_sku_position = $position;
					}
					if($attribute == 'bridge_images'){
						$bridge_images_position = $position;
					}
					if($attribute == 'cross_sells_skus'){
						$cross_sells_position = $position;
					}
					if($attribute == 'up_sells_skus'){
						$up_sells_positon = $position;
					}
					if($attribute == 'related_skus'){
						$related_products_position = $position;
					}
					$position++;
				}
				
				//there isn't sku field in document
				if($sku_position === ''){
					$this->_helper->insertLog('FATAL ERROR', 'sku field missing.', 'function prepareData');
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
					Mage::getModel('pixxy_csvimport/email')->send($file->getFilenameOriginal(), '- Erorr: Please check this file, it seems that sku field missing');
					return;
				}
				
				$tmp_positions = Mage::getModel('pixxy_csvimport/tmppositions')->getCollection();
				
				//filters
				if($mapping_profile->getFilterTypeId() || $mapping_profile->getFilterAttributeSetId()){
					if($mapping_profile->getFilterTypeId()){
						$this->_filter_type_id = explode(',', $mapping_profile->getFilterTypeId());
					}
					if($mapping_profile->getFilterAttributeSetId()){
						$this->_filter_attribute_set_id = explode(',', $mapping_profile->getFilterAttributeSetId());
					}
					if(count($this->_filter_type_id)){
						$this->_filter_type_id_enabled = true;
					}
					if(count($this->_filter_attribute_set_id)){
						$this->_filter_attribute_set_id_enabled = true;
					}
				}
				
				foreach ($csv_import as $d){
					if(!file_exists(Mage::getBaseDir()."/var/csv_import.flag")){
						//stop import process
						return;
					}
					$notify_qty = false;
					$product_data = array();
					foreach ($tmp_positions as $tmp_position){
						if(isset($d[$tmp_position->getPosition()])){
							$product_data[$tmp_position->getMgAttribute()] = $d[$tmp_position->getPosition()];
						}
						else{
							$this->_helper->insertLog('FATAL ERROR', 'Please check file, it seems that some colums missing.', 'function prepareData');
							//$file->setErrorsWarnings((int)$file->getErrorsWarnings()+1);
							$file->setCountRows('999999');
							//$file->setImportedRows('999999');
							$file->setActive('0');
							$file->setDateImportEnd(Mage::getModel('core/date')->gmtDate());
							$file->setIndexed('1');
							$file->save();
							if($this->_helper->getConfig('cronsettings/auto_delete_imported_files')){
								if(file_exists($file->getFileLocation())){
									unlink($file->getFileLocation());
								}
							}
							Mage::getModel('pixxy_csvimport/email')->send($file->getFilenameOriginal(), '- Erorr: Please check this file, it seems that some colums missing');
							return;
						}
					}
					//if update stock is selected add that field to product_data array
					if($mapping_profile->getUpdateStock() == '1'){
						if($qty_position != ''){
							if(isset($d[$qty_position])){
								$product_data['qty'] = $d[$qty_position];
							}
							else{
								$this->_helper->insertLog('FATAL ERROR', 'Please check file, it seems that some colums missing.', 'function prepareData');
								//$file->setErrorsWarnings((int)$file->getErrorsWarnings()+1);
								$file->setCountRows('999999');
								//$file->setImportedRows('999999');
								$file->setActive('0');
								$file->setDateImportEnd(Mage::getModel('core/date')->gmtDate());
								$file->setIndexed('1');
								$file->save();
								if($this->_helper->getConfig('cronsettings/auto_delete_imported_files')){
									if(file_exists($file->getFileLocation())){
										unlink($file->getFileLocation());
									}
								}
							return;
							}
						}
						else{
							$notify_qty = true;
							//$this->_helper->insertLog($d[$sku_position], 'Qty field for this sku don\'t exist. Stock status can\'t be updated.', 'function prepareData');
						}
					}
					//category_ids positions
					if($category_ids_position != ''){
						if(isset($d[$category_ids_position]) && !empty($d[$category_ids_position])){
							$product_data['category_ids'] = $d[$category_ids_position];
						}
					}
					//is sku isn't empty
					if($d[$sku_position] == ""){
						$this->_helper->insertLog('Empty sku field', 'You have empty sku field in data', 'function prepareData');
						continue;
					}
					//bridge_images_position
					if($bridge_images_position != ''){
						if(!empty($d[$bridge_images_position])){
							$product_data['bridge_images'] = $d[$bridge_images_position]; 
						}
					}
					//cross, up sells and related products
					if($cross_sells_position != ''){
						if(!empty($d[$cross_sells_position])){
							$product_data['cross_sells_skus'] = $d[$cross_sells_position]; 
						}
					}
					if($up_sells_positon != ''){
						if(!empty($d[$up_sells_positon])){
							$product_data['up_sells_skus'] = $d[$up_sells_positon]; 
						}
					}
					if($related_products_position != ''){
						if(!empty($d[$related_products_position])){
							$product_data['related_products'] = $d[$related_products_position]; 
						}
					}
					if($parent_sku_position != ''){
						if(!empty($d[$parent_sku_position])){
							$product_data['parent_sku'] = trim($d[$parent_sku_position]);
						}
					}
					
					if(count($product_data)){
						$this->loadProductBySku($d[$sku_position]);
						//if product exist in database
						if($this->_product !== null){
							//check if profile isn't import only 
							if($mapping_profile->getMode() == '1'){
								//$this->_helper->insertLog($d[$sku_position], 'This sku can\'t be updated because selected mode is Import only. This product exist in database.', 'function prepareData');
								continue;
							}
							if($notify_qty == true){
								$this->_helper->insertLog($d[$sku_position], 'Qty field for this sku don\'t exist. Stock status can\'t be updated.', 'function prepareData');
							}
							//call updateProductData function
							$this->_helper->log($d[$sku_position].'-------------------------------------------------------------------------------');
							$this->updateProductData($d[$sku_position], $product_data); 
							unset($product_data);
						}
						//if product don't exist
						else{
							//check if profile isn't update only
							if($mapping_profile->getMode() == '2'){
								//$this->_helper->insertLog($d[$sku_position], 'This sku didn\'t created because selected mode is Update only. This product don\'t exist in database.', 'function prepareData');
								continue;
							}
							if($notify_qty == true){
								$this->_helper->insertLog($d[$sku_position], 'Qty field for this sku don\'t exist. Product can\'t be saved.', 'function prepareData');
								continue;
							}
							if(isset($d[$attribute_set_id_position])){
								$as = $d[$attribute_set_id_position];
								$attribute_set = Mage::getModel("eav/entity_attribute_set")->load($as);
								if($attribute_set && $attribute_set->getId()){
									$product_data['attribute_set_id'] = $attribute_set->getId();
								}
								else{
									$entityTypeId = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
									$attribute_set = Mage::getResourceModel('eav/entity_attribute_set_collection')->setEntityTypeFilter($entityTypeId)->addFieldToFilter('attribute_set_name', array('eq'=>trim($as)))->getFirstItem();
									if($attribute_set && $attribute_set->getId()){
										$product_data['attribute_set_id'] = $attribute_set->getAttributeSetId();
									}
									else{
										$this->_helper->insertLog($d[$sku_position], $as.'attribute_set_id field can\'t be found for this sku. Product can\'t be created.', 'function prepareData');
										continue;
									}
								}
							}
							else{
								$this->_helper->insertLog($d[$sku_position], 'attribute_set_id field for this sku don\'t exist. Product can\'t be created.', 'function prepareData');
								continue;
							}
							if(isset($d[$type_id_position]) && !empty($d[$type_id_position])){
								$product_data['type_id'] = trim($d[$type_id_position]);
							}
							else{
								$this->_helper->insertLog($d[$sku_position], 'type_id field for is empty. Product can\'t be created.', 'function prepareData');
								continue;
							}
							$match = 0;
							foreach ($product_data as $key => $value){
								if(in_array($key, $this->_required)){
									$match++;
								}
							}
							if($match < count($this->_required)){
								$this->_helper->insertLog($d[$sku_position], 'Missing required attributes. Product can\'t be created. Required attributes are: '.implode(',', $this->_required), 'function prepareData');
								continue;
							}
							//call importProductData function
							$this->_helper->log($d[$sku_position].'-------------------------------------------------------------------------------');
							$this->createNewProduct($d[$sku_position], $product_data);
							unset($product_data);
						}
					}
					else{
						$this->_helper->insertLog($d[$sku_position], 'Product data for this sku is empty (All mapped and automatically mapped attribute values are empty). This product isn\'t updated', 'function prepareData');
					}
				}
				if(isset($csv_import)){
					unset($csv_import);
				}
			}
		}
		
		/**
		 * GET ATTRIBUTE CODES FROM EXISTING PRODUCT
		 */
		public function getAttributeCodesFEP(){
			return Mage::getModel('eav/config')->getEntityAttributeCodes(
						Mage_Catalog_Model_Product::ENTITY,
						$this->_product
					);
		}
		
		/**
		 * SET PRODUCT ATTRIBUTES (SELECT, MULTIPLE OR OTHER TYPES (TEXT, DATE...)) 
		 */
		public function setAttributes($key, $value, $productAttributes){
			if(!empty($value)){
				if(in_array($key, $productAttributes) && $key!="sku"){
					//$this->_helper->log($this->_product->getSku()." - processing attribute -> ".$key." = ".$value);
					$mgAttribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $key);
					//if attribute input type is select
					if($mgAttribute->getFrontendInput() == 'select'){
						if($mgAttribute->usesSource()){
							$options = $mgAttribute->getSource()->getAllOptions(true, true);
							$option_exist = false;
							foreach ($options as $option){
								if(strcasecmp((string)($option['label']), (string)$value) === 0){
									$this->_product->setData($key, $option['value']);
									$option_exist = true;
								}
							}
							if($this->_helper->getConfig('attroptions/addoptions') && !$option_exist && !in_array($key, $this->_required) && $mgAttribute->getIsUserDefined() && !in_array($value, $this->_error_codes)){
								$this->_helper->log($this->_product->getSku()." - Attribute ".$key." - adding new option ".$value);
								$mgAttribute->setData('option', array(
									'value' => array(
										'option' => array((string)$value),
									),
								));
								$mgAttribute->save();
								$mgAttribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $key);
								$options = $mgAttribute->getSource()->getAllOptions(true, true);
								foreach ($options as $option){
									if(strcasecmp((string)($option['label']), (string)$value) === 0){
										$this->_product->setData($key, $option['value']);
									}
								}
							}
						}
					}
					//if attribute input type is multiselect
					else if($mgAttribute->getFrontendInput() == 'multiselect'){
						if($this->_helper->getConfig('attroptions/addmoptions') && $mgAttribute->getIsUserDefined()){
							$multiselectOptions = $mgAttribute->getSource()->getAllOptions(true, true);
							$allOptions = array();
							foreach ($multiselectOptions as $ms){
								$allOptions[] = $ms['label'];
							}
							$valuesText = explode(',', $value);
							$valuesText = array_unique($valuesText);
							foreach ($valuesText as $option){
								if(!in_array($option, $allOptions) && !in_array($option, $this->_error_codes)){
									//add new option
									$mgAttribute->setData('option', array(
										'value' => array(
											'option' => array((string)$option),
										),
									));
									$mgAttribute->save();
									$mgAttribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $key);
								}
							}
						}
						//map multiselect options
						$sourceModel = $mgAttribute->getSource();//Mage::getModel('catalog/product')->getResource()->getAttribute($key)->getSource();
						$valuesText = explode(',', (string)$value);
						$valuesIds = array_map(array($sourceModel, 'getOptionId'), $valuesText);
						$this->_product->setData($key, $valuesIds);
					}
					else{
						//skip images and fill other attributes
						if(!in_array($key, $this->_image_fields)){
							$this->_product->setData($key, $value);
						}
					}
				}
			}
			else{
				if($this->_helper->getConfig('attroptions/process_empty_options')){
					if(!in_array($key, $this->_required)){
						$this->_product->setData($key, "");
					}
				}
			}
		}
		
		/**
		 * UPDATE EXISTING PRODUCT DATA
		 */
		public function updateProductData($sku, $product_data, $custom = null){
			try {
				//use function with other modules
				if($custom !== null){
					$this->loadProductBySku($sku);
				}
				//if product exist in database
				if($this->_product !== null){
					//filters
					if($this->_filter_type_id_enabled){
						if(!in_array($this->_product->getTypeId(), $this->_filter_type_id)){
							$this->_helper->log($this->_product->getSku()." - don\'t match with filter type_id - SKIPPED");
							return;
						}
					}
					if($this->_filter_attribute_set_id_enabled){
						if(!in_array($this->_product->getAttributeSetId(), $this->_filter_attribute_set_id)){
							$this->_helper->log($this->_product->getSku()." - don\'t match with filter attribute_set_id - SKIPPED");
							return;
						}
					}
					foreach ($product_data as $key=>$value) {
						//skip image field and process images
						if(in_array($key, $this->_image_fields)){
							$this->_process_images = true;
							continue;
						}
						if(in_array($key, $this->_image_label_fields)){
							$this->_process_image_labels = true;
							continue;
						}
						//need to add some skipping attributes
						$productAttributes = $this->getAttributeCodesFEP();
						//if product have attribute set it value
						$this->setAttributes($key, $value, $productAttributes);
						/***
						 * update stock status
						 */
						if($key == 'qty'){
						//if(isset($product_data['qty'])){
							if($product_data['qty'] != ''){
								$qty_stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($this->_product)->getQty();
								if((double)$qty_stock != (double)$product_data['qty']){
									$is_in_stock = 1;
									if((double)$product_data['qty'] == 0.00){
										$is_in_stock = 0;
									}
									$this->_product->setStockData(array(
										'is_in_stock' => $is_in_stock,
										'qty' => $product_data['qty'],
									));
									//$this->_helper->log($this->_product->getSku()." - "."QTY set to ".$product_data['qty']);
								}
							}
							else{
								if($this->_product->getTypeId() != 'configurable'){
									$this->_helper->insertLog($sku, 'Quantity not updated for this sku because value is empty.', 'function updateProductData');
								}
							}
						//}
						}
						
						if($key == 'category_ids' && !empty($value)){
							$category_ids = explode(',', $value);
							$categories = array();
							foreach ($category_ids as $cat){
								//if category ids
								if(is_numeric($cat)){
									$category = Mage::getModel('catalog/category')->load(trim($cat));
								}	
								else{
									$category = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', trim($cat))->getFirstItem();
								}
								if($category && $category->getId()){
									$categories[] = $category->getId();
								}
								else{
									$this->_helper->insertLog($sku, "Failed to save category. Category id $cat don\'t exist", 'function updateProductData');
								}
							}
							if(count($categories)){
								$this->_product->setCategoryIds($categories);
							}
						}
						
						if($key == 'bridge_images'){
							$bridge_images = explode(',', $value);
							if(count($bridge_images)){
								//DELETE OLD IMAGES
								if(Pixxy_Csvimport_Model_Image::DELETE_ALL_IMAGES){
									$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
								    $items = $mediaApi->items($this->_product->getId());
								    foreach($items as $item) {
								    	$ioObject = new Varien_Io_File();
								    	$mediaApi->remove($this->_product->getId(), $item['file']);
								    	$destDirectory = dirname(Mage::getSingleton('catalog/product_media_config')->getMediaPath($item['file']));
									    try {
										    $ioObject->open(array('path'=>$destDirectory));
										    $ioObject->rm(Mage::getSingleton('catalog/product_media_config')->getMediaPath($item['file']));
									    } catch (Exception $e) {
										    Mage::log('Unable to delete image '.$item['file'], null, $file = 'csv_import_images.log', true);
								   	 	}
								    }
								}
							    $this->_product = Mage::getModel('catalog/product')->load($this->_product->getId());
								$this->_helper->log($sku."->processing bridge image");
								foreach ($bridge_images as $bridge_image){
									foreach (Pixxy_Csvimport_Model_Image::channelAdvisorImageFields() as $bridge_field => $bridge_value){
										if(substr($bridge_image, 0, (int)strlen($bridge_field)) == $bridge_field){
											if(is_array($bridge_value)){
												foreach ($bridge_value as $b){
													if($this->importImage(substr($bridge_image, 1+(int)strlen($bridge_field)), $b)){
														$this->_product->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)->save();
														//load product again
														$this->_product = Mage::getModel('catalog/product')->load($this->_product->getId());
													}
												}
											}
											else{
												if($this->importImage(substr($bridge_image, 1+(int)strlen($bridge_field)))){
													$this->_product->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)->save();
													//load product again
													$this->_product = Mage::getModel('catalog/product')->load($this->_product->getId());
												}
											}
										}
									}
								}
							}
						}
						
						if(isset($product_data['parent_sku']) && $this->_product->getTypeId() != 'configurable'){
						//set parent sku in database
							if($product_data['parent_sku'] != '' || $product_data['parent_sku'] != ' '){
								$parent = Mage::getModel('pixxy_csvimport/parent');
								$parent->setFileId($this->getActiveFile()->getId());
								$parent->setSku($sku);
								$parent->setParentSku($product_data['parent_sku']);
								$parent->save();
							}
						}
						
						if($key == 'cross_sells_skus' || $key == 'up_sells_skus' || $key == 'related_products'){
							$cur_products = explode(',', $value);
							if(count($cur_products)){
								foreach ($cur_products as $cur_product_sku){
									$cur_product_model = Mage::getModel('pixxy_csvimport/curproducts');
									$cur_product_model->setFileId($this->getActiveFile()->getId());
									$cur_product_model->setSku($this->_product->getSku());
									$cur_product_model->setCurProduct(trim($cur_product_sku));
									$cur_product_model->setCurProductType($key);
									$cur_product_model->save();
								}
							}
						}
						
						/***********************
						 * custom mapping here *
						 * example: if($key == "blabla") {$product->setData($key, $value)}
						 ***********************/
						
					}
					/**
					 * save product
					 */
					$this->_product->setUpdatedAt(strtotime('now'));
					$this->_product->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)->save();
					
					/**
					 * process images
					 */
					if($this->_process_images == true){
						//DELETE OLD IMAGES
						if(Pixxy_Csvimport_Model_Image::DELETE_ALL_IMAGES){
							$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
						    $items = $mediaApi->items($this->_product->getId());
						    foreach($items as $item) {
						    	$ioObject = new Varien_Io_File();
						    	$mediaApi->remove($this->_product->getId(), $item['file']);
						    	$destDirectory = dirname(Mage::getSingleton('catalog/product_media_config')->getMediaPath($item['file']));
							    try {
								    $ioObject->open(array('path'=>$destDirectory));
								    $ioObject->rm(Mage::getSingleton('catalog/product_media_config')->getMediaPath($item['file']));
							    } catch (Exception $e) {
								    Mage::log('Unable to delete image '.$item['file'], null, $file = 'csv_import_images.log', true);
						   	 	}
						    }
						    $this->_product = Mage::getModel('catalog/product')->load($this->_product->getId());
						}
						foreach ($product_data as $key=>$value) {
							if(in_array($key, $this->_image_fields)){
								if($value){
									if($key != 'gallery'){
										//$this->_helper->log("Image ".$key." import start");
										$image_field = $key;
										if(!in_array($key, $this->_selecting_image_fields)){
											$image_field = null;
										}
										if($this->importImage($value, $image_field)){
											$this->_product->setUpdatedAt(strtotime('now'));
											$this->_product->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)->save();
											//load product again
											$this->_product = Mage::getModel('catalog/product')->load($this->_product->getId());
										}
									//$this->_helper->log("Image ".$key." import end");
									}
									else{
										$image_urls = explode(',', $value);
										foreach ($image_urls as $image_url){
											//$this->_helper->log("Image - *gallery* - ".$image_url." import start");
											if($this->importImage(trim($image_url))){
												$this->_product->setUpdatedAt(strtotime('now'));
												$this->_product->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)->save();
												//load product again
												$this->_product = Mage::getModel('catalog/product')->load($this->_product->getId());
											}
											//$this->_helper->log("Image - *gallery* - ".$image_url." import end");
										}
									}
								}
							}
						}
					}
					
					/**
					 * process image labels
					 */
					if($this->_process_image_labels == true){
						foreach ($product_data as $key=>$value) {
							if(in_array($key, $this->_image_label_fields)){
								if($value){
									$mediaApi = Mage::getModel("catalog/product_attribute_media_api"); 
								    $items = $mediaApi->items($this->_product->getId());
								    foreach ($items as &$image){
									    if(count($image['types'])){
									  		foreach ($image['types'] as $type){
									  			foreach (Pixxy_Csvimport_Model_Image::getMappedImageLabels() as $ilfkey => $ilfvalue){
									  				if($ilfkey == $type && $ilfvalue == $key){
										  				$mediaApi->update(
													      	$this->_product->getId(),
													      	$image['file'],
													      	array("label"=>trim($value))
													    );
									  				}
									  			}
									  		}
									  	}
								    }
								}
							}
						}
					}
					
				}
				else{
					$this->_helper->insertLog($sku, 'This product don\'t exist in database.', 'function updateProductData');
				}
			} catch (Exception $e) {
				$this->_helper->insertLog($sku, "Product data update failed. Reason: ".$e->getMessage(), $e);
				$this->_helper->log("Error ".$e->getMessage());
			}
		}
		
		/**
		 * CREATE NEW PRODUCT
		 */
		public function createNewProduct($sku, $product_data){
			$this->_product = Mage::getModel('catalog/product');
			try {
				$this->_helper->log("$sku - Creating new product start - sku: ");
				//set product website_ids
				$websites = Mage::getModel('core/website')->getCollection();
				$website_ids = array();
				foreach ($websites as $website){
					$website_ids[] = $website->getId();									
				}
				$this->_product->setWebsiteIds($website_ids);
				//set store_id
				$this->_product->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID);
				$this->_product->setSku($sku);
				
				//filters
				if($this->_filter_type_id_enabled){
					if(!in_array($product_data['type_id'], $this->_filter_type_id)){
						$this->_helper->log($this->_product->getSku()." - don\'t match with filter type_id - SKIPPED");
						return;
					}
				}
				if($this->_filter_attribute_set_id_enabled){
					if(!in_array($product_data['attribute_set_id'], $this->_filter_attribute_set_id)){
						$this->_helper->log($this->_product->getSku()." - don\'t match with filter attribute_set_id - SKIPPED");
						return;
					}
				}
				
				$this->_product->setAttributeSetId($product_data['attribute_set_id']);
				$this->_product->setTypeId($product_data['type_id']);
				$this->_product->setCreatedAt(strtotime('now'));
				$this->_product->setStockData(array(
                       'use_config_manage_stock' => 0, 
                       'manage_stock'=>1, 
					   'min_sale_qty'=>1, 
                       'max_sale_qty'=>10000,
				));
				foreach ($this->_required as $required){
					//$this->_helper->log("$sku - setting required field ".$required."=".$product_data[$required]);
					$this->_product->setData($required, $product_data[$required]);
				}
				$this->_product->save();
				$this->_product = Mage::getModel('catalog/product')->load($this->_product->getId());
				$this->_helper->log("$sku - Product created");
				//check for duplicate url key
				$url_key = $this->_product->getUrlKey();
				if($this->isKeyExist($url_key, 1)){
					$product_data['url_key'] = $url_key."-".time().rand(1, 1000)."-duplicate-url";
				}
				
				$this->updateProductData($this->_product->getSku(), $product_data);
			} catch (Exception $e) {
				$this->_helper->insertLog($sku, "ERROR while saving new product. Reason: ".$e->getMessage(), $e);
				$this->_helper->log("$sku - Error ".$e->getMessage());
			}
		}

		/**
		 * IMAGE IMPORT FUNCTION
		 * 
		 */		
		public function importImage($location, $field = null){
			$this->_helper->log("Processing image from location - ".$location);
			$image_downloaded = false;
			if(substr($location, 0, 4) != 'http'){
				//$this->_helper->log("Image is located on this server: ".$location);
				$location = Mage::getBaseDir().$location;
			}
			else{
				//$this->_helper->log("Image is located on another server: ".$location);
				$importPath = Mage::getBaseDir('media') . DS . 'image_import'. DS;
				if(!file_exists($importPath)){
					mkdir($importPath);
				}
				$ch = curl_init();
	
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set cURL to return the data instead of printing it to the browser.
				curl_setopt($ch, CURLOPT_URL, trim($location));
		
				$data = curl_exec($ch);
				if($data){
					curl_close($ch);
					$basename = pathinfo($location);
					$basename = $basename['basename'];
					$location   = $importPath.$basename;
					if(file_exists($location)){
						unlink($location);
					}
					file_put_contents($location, $data);
					$image_downloaded = true;
				}
				else{
					$this->_helper->log("Failed to download image from: ".$location);
					$this->_helper->insertLog($this->_product->getSku(), "Failed to download image from: ".$location, 'importImage function');
					return false;
				}
			}
			
			if(file_exists($location)){
				try {
					$md5image_new = md5(file_get_contents($location)); 
					$path_info_new_image = pathinfo($location);
		  				
	  				//checking if product allready have that image
	  				$media_gallery_images = $this->_product->getMediaGallery('images');
	  				if(count($media_gallery_images)){
			  			foreach ($media_gallery_images as $image) {
		            		$image_file = $image['file'];
	            			$old_image_path = Mage::getBaseDir('media').'/catalog/product'.$image_file;
	            			if(file_exists($old_image_path)){
				  				$md5image_old = md5(file_get_contents($old_image_path));
				  				if($md5image_new == $md5image_old){
				  					$this->_product->setData($field, $image_file);
				  					//$this->_helper->log("Product allready have same image.".$image_file."==".$path_info_new_image['basename']);
				  					//$this->_helper->log("EXIT");
				  					if($image_downloaded && file_exists($location)){
				  						unlink($location);
				  					}
				  					if($field != null){
				  						return true;
				  					}
				  					return false;
				  				}
	            			}
	            			else{
	            				$this->_helper->log("Error loading image. This image don\'t exist: ".$old_image_path." Check!");
	            			}
						}  
	  				}
					//$this->_helper->log("Saving image to media gallery");
					$this->_product->addImageToMediaGallery($location , $field, false, false);
					//$this->_helper->log("Saving image complete");
					if($image_downloaded && file_exists($location)){
  						unlink($location);
  					}
					return true;
				}
				catch (Exception $e) {
					$this->_helper->insertLog($this->_product->getSku(),"Error while saving image $location: ".$e->getMessage(),'importImage function');
					$this->_helper->log("FATAL ERROR: ".$e);
				}
			}
			else{
				$this->_helper->insertLog($this->_product->getSku(), "Image don\'t exist: ".$location, 'importImage function');
				$this->_helper->log("Image dont exist at this location: ".$location);
			}
			return false;
		}

		/**
		 * Check if duplicate product url_key exist in database 
		 */
		public function isKeyExist($urlkey, $num){
			$collection=Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('url_key')->addAttributeToFilter('url_key',$urlkey);
			if($collection->getSize()>$num){
				return true;
			}
			return false;
		}
		
	}
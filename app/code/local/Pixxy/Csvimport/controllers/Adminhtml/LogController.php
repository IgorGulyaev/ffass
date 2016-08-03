<?php	

	class Pixxy_Csvimport_Adminhtml_LogController extends Mage_Adminhtml_Controller_Action{
		
		public function indexAction(){
			$_attr = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'url_key');
			if($_attr && $_attr->getId()){
				$_table = Mage::getSingleton('core/resource')->getTableName('catalog_product_entity_varchar');
				$_connection = Mage::getSingleton('core/resource')->getConnection('eav_write');
				$select = $_connection->select()->from($_table, array(
					'num' => new Zend_Db_Expr('COUNT(*)'),
					'url_key' => 'value',
					'store' => 'store_id'
				))
				->where('attribute_id=?', $_attr->getId())
				->group('value')
				->group('store_id')
				->order('num')
				->having('num > 1');
				Mage::getResourceHelper('core')->addGroupConcatColumn($select, 'entities', 'entity_id');
				$result = $_connection->fetchAll($select);
				if(sizeof($result) > 0){
					Mage::getSingleton('adminhtml/session')->addWarning("This site has duplicate url keys. This can cause problems to indexers and importer speed.");
				}
			}
			$this->loadLayout();
			$this->renderLayout();
		}
		
		public function detailsAction(){
			$this->loadLayout();
			$this->renderLayout();
		}
		
		public function exportCsvAction(){
		    $fileName   = 'csvimport-errors-warnings.csv';
		    $content    = $this->getLayout()->createBlock('csvimport/adminhtml_logfile_grid')->getCsvFile();
		    $this->_prepareDownloadResponse($fileName, $content);
		}
		
		public function clearlogAction(){
			$general_log_file = Mage::getBaseDir().'/var/log/csv_import.log';
			if(file_exists($general_log_file)){
				unlink($general_log_file);
				Mage::getSingleton('adminhtml/session')->addSuccess('csv_import.log cleared!');
			}
			$scanner_log_file = Mage::getBaseDir().'/var/log/csv_import_scanner.log';
			if(file_exists($scanner_log_file)){
				unlink($scanner_log_file);
			}
			$this->_redirect('*/system_config/edit/section/csvimport');
		}
		
		public function deletefileAction(){
			if($this->getRequest()->getParam('id')){
				$file = Mage::getModel('pixxy_csvimport/file')->load($this->getRequest()->getParam('id'));
				if($file && $file->getId()){
					Mage::getModel('pixxy_csvimport/file')->deleteFile($file->getId());
					Mage::getSingleton('adminhtml/session')->addSuccess("File deleted");
				}
			}
			$this->_redirect('*/*/index');
		}
		
		public function stopimportAction(){
			if($this->getRequest()->getParam('id')){
				$file = Mage::getModel('pixxy_csvimport/file')->load($this->getRequest()->getParam('id'));
				if($file && $file->getId()){
					if(file_exists(Mage::getBaseDir()."/var/csv_import.flag")){
						unlink(Mage::getBaseDir()."/var/csv_import.flag");
					}
					Mage::getModel('pixxy_csvimport/file')->deleteFile($file->getId());
					Mage::getSingleton('adminhtml/session')->addSuccess("Import/update proccess stopped and file deleted");
				}
			}
			$this->_redirect('*/*/index');
		}
		
	}
<?php

	class Pixxy_Csvimport_Adminhtml_UploadController extends Mage_Adminhtml_Controller_Action{
		
		public function indexAction(){
			$this->loadLayout();
			if(!count(Mage::getModel('pixxy_csvimport/mappingprofile')->getCollection())){
				Mage::getSingleton('adminhtml/session')->addWarning('Please create first mapping profile, then upload file.');
				$this->_redirect('*/mapping/new');
			}
			$this->renderLayout();
		}
		
		public function saveAction(){
			if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != ''){
				try {
					$file_location = Mage::getBaseDir().Mage::getStoreConfig('csvimport/general/file_location');
					$uploader = new Varien_File_Uploader('file');
					$uploader->setAllowedExtensions(array('txt','csv'));
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $last_file = Mage::getModel('pixxy_csvimport/file')->getCollection()->getLastItem();
                    
                    $count = 1;
                    if($last_file && $last_file->getId()){
                    	$count = $last_file->getCount()+1;
                    }
                    while(file_exists(Mage::getBaseDir().Mage::getStoreConfig('csvimport/general/file_location').'import_'.$count.'.csv')){
                    	$count++;
                    }
                    $name = 'import_'.$count.'.csv';
                    $uploader->save($file_location, $name);
                    
                    $data = array(
                    	'profile_id' => $this->getRequest()->getParam('profile_name'),
                    	'file_location' => $file_location.$name,
                    	'filename_original' => $_FILES['file']['name'],
                    	'count_rows' => '0',
                    	'imported_rows' => '0',
                    	'active' => '0',
                    	'ordinal_number' => $count,
                    	'indexed' => '0',
                    	'date_uploaded' => date(now()),
                    	'user' => Mage::getSingleton('admin/session')->getUser()->getUsername(),
                    );
                    
                    $file = Mage::getModel('pixxy_csvimport/file');
                    $file->setData($data);
                    $file->setDelimiter(Pixxy_Csvimport_Model_File::getDelimiterFromFile($file_location.$name));
                    $file->setFileSize(filesize($file_location.$name));
                    $file->save();
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('csvimport')->__('File uploaded successfully!'));
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('csvimport')->__('Error while saving file. '.$e->getMessage()));
				}
			}
			$this->_forward('index');
		}
		
	}

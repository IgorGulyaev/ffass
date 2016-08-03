<?php

	class Pixxy_Csvimport_Model_Scanner{
		
		private $_fileLocation;
		private $_helper;
		
		public function __construct(){
			$this->_helper = Mage::helper('csvimport');
			$this->_fileLocation = Mage::getBaseDir().$this->_helper->getConfig('general/file_location');
		}
		
		public function scan(){
			$mapping_profiles = Mage::getModel('pixxy_csvimport/mappingprofile')->getCollection()->addFieldToFilter('hide', array('eq' => '0'));
			if(count($mapping_profiles)){
				foreach($mapping_profiles as $mapping_profile){
					$mapping_profile_name = Mage::getModel('pixxy_csvimport/file')->clean($mapping_profile->getProfileName());
					if(!file_exists($this->_fileLocation.$mapping_profile_name)){
						mkdir($this->_fileLocation.$mapping_profile_name, 0777, true);
					}
					//$directory_files = array_diff(scandir($this->_fileLocation.$mapping_profile_name), array('..', '.'));
					$directory_files = array();
					$handle = opendir($this->_fileLocation.$mapping_profile_name);
				    while (false !== ($entry = readdir($handle))) {
				        $directory_files[] = $entry;
				    }
					if(count($directory_files)){
						$imported_filenames = array();
						$files = Mage::getModel('pixxy_csvimport/file')->getCollection()->addFieldToFilter('profile_id', array('eq' => $mapping_profile->getId()));
						foreach ($files as $file){
							foreach ($directory_files as $directory_file){
								if(in_array(substr($directory_file, -4), array('.txt', '.csv'))){
									if(file_exists($file->getFileLocation())){
										if($file->getFilenameOriginal() == $directory_file && filesize($file->getFileLocation()) == filesize($this->_fileLocation.$mapping_profile_name.'/'.$directory_file)){
											$imported_filenames[] = $file->getFilenameOriginal();
										}
									}
									else{
										//$imported_filenames[] = $file->getFilenameOriginal();
										/**
										 * @FIX
										 */
										if($file->getFilenameOriginal() == $directory_file && $file->getFileSize() == filesize($this->_fileLocation.$mapping_profile_name.'/'.$directory_file)){
											$imported_filenames[] = $file->getFilenameOriginal();
										}
									}
								}
							}
						}
						foreach ($directory_files as $directory_file){
							if(!in_array($directory_file, $imported_filenames) && in_array(substr($directory_file, -4), array('.txt', '.csv'))){
								try {
									$filesize_before_sleep = filesize($this->_fileLocation.$mapping_profile_name.'/'.$directory_file);
									sleep(10);
									$filesize_after_sleep = filesize($this->_fileLocation.$mapping_profile_name.'/'.$directory_file);
									if($filesize_before_sleep != $filesize_after_sleep){
										//skip file (uploading to server is in progress)
										continue;
									}
									$file_location = $this->_fileLocation.$mapping_profile_name;
				                    $last_file = Mage::getModel('pixxy_csvimport/file')->getCollection()->getLastItem();
				                    
				                    $count = 1;
				                    if($last_file && $last_file->getId()){
				                    	$count = $last_file->getCount()+1;
				                    }
				                    while(file_exists(Mage::getBaseDir().Mage::getStoreConfig('csvimport/general/file_location').'import_'.$count.'.csv')){
				                    	$count++;
				                    }
				                    $name = 'import_'.$count.'.csv';
				                    
				                    if(!copy($this->_fileLocation.$mapping_profile_name.'/'.$directory_file, $this->_fileLocation.$name)){
				                    	$this->_helper->scannerLog('FAILED TO COPY FILE '.$this->_fileLocation.$mapping_profile_name.'/'.$directory_file);	
				                    }
				                    else {
				                    	if(file_exists($this->_fileLocation.$mapping_profile_name.'/'.$directory_file) && $this->_helper->getConfig('cronsettings/auto_delete_scanner_files')){
											unlink($this->_fileLocation.$mapping_profile_name.'/'.$directory_file);				                    		
				                    	}
				                    }
				                    
				                    $data = array(
				                    	'profile_id' => $mapping_profile->getId(),
				                    	'file_location' => $this->_fileLocation.$name,
				                    	'filename_original' => $directory_file,
				                    	'count_rows' => '0',
				                    	'imported_rows' => '0',
				                    	'active' => '0',
				                    	'ordinal_number' => $count,
				                    	'indexed' => '0',
				                    	'date_uploaded' => Mage::getModel('core/date')->gmtDate(),
				                    	'user' => 'CSV Import FTP Scanner',
				                    );
				                    
				                    $file = Mage::getModel('pixxy_csvimport/file');
				                    $file->setData($data);
				                    $file->setDelimiter(Pixxy_Csvimport_Model_File::getDelimiterFromFile($this->_fileLocation.$name));
				                    $file->setFileSize(filesize($this->_fileLocation.$name));
				                    $file->save();
								} catch (Exception $e) {
									$this->_helper->scannerLog('Error while saving file. '.$e->getMessage());
								}
							}
						}				
					}
				}
			}
		}
		
		public function check(){
			if(file_exists(Mage::getBaseDir()."/var/csv_import.flag")){
				if(Mage::getModel('pixxy_csvimport/file')->importInProgress()){
					$active_file = Mage::getModel('pixxy_csvimport/file')->getCollection()->addFieldToFilter('active', array('eq'=>'1'))->getFirstItem();
					if($active_file && $active_file->getId()){
						$last_ping = strtotime($active_file->getLastPing());
						if($last_ping){
							$now = strtotime(Mage::getModel('core/date')->gmtDate());
							if((int)$now - (int)$last_ping > 10*(int)$this->_helper->getConfig('cronsettings/count_import')){
								if((int)$active_file->getImportedRows()>0){
									if((int)$active_file->getImportedRows() - (int)$this->_helper->getConfig('cronsettings/count_import') >= 0){
										$active_file->setImportedRows((int)$active_file->getImportedRows() - (int)$this->_helper->getConfig('cronsettings/count_import'));
									}
									else {
										$active_file->setImportedRows('0');
									}
									$active_file->save();
									$this->_helper->scannerLog('Last ping > 3 hours. Check file id - '.$active_file->getId());	
								}
								unlink(Mage::getBaseDir()."/var/csv_import.flag");
								$this->_helper->scannerLog('Last ping > 3 hours. Check file id - '.$active_file->getId());	
							}
						}
					}
					else{
						unlink(Mage::getBaseDir()."/var/csv_import.flag");
					}
				}
				else{
					unlink(Mage::getBaseDir()."/var/csv_import.flag");
				}
			}
		}
		
	}
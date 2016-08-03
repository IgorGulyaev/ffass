<?php

	class Pixxy_Csvimport_Model_Resource_Mappingprofile_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{
		
		public function _construct(){
			$this->_init('pixxy_csvimport/mappingprofile');
		}
		
		public function toOptionArray(){
	        $collection = $this;
	        $data = array();
	        foreach ($collection as $profile){
	        	$data[] = array(
	        		'value' => $profile->getId(),
	        		'label' => $profile->getProfileName(),
	        	);
	        }
	        return $data;
	    }		
	    
	}
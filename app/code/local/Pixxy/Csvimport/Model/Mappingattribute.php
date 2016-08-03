<?php

	class Pixxy_Csvimport_Model_Mappingattribute extends Mage_Core_Model_Abstract{
		
		protected function _construct(){
			$this->_init('pixxy_csvimport/mappingattribute');
		}
		
		/**
		 * Return mapped attributes by profile_id 
		 */
		public function getMappedAttributes($profile_id){
			$attributes = $this->getCollection()->addFieldToFilter('mappingprofile_id', array('eq'=>$profile_id));
			return $attributes;
		}
		
	}
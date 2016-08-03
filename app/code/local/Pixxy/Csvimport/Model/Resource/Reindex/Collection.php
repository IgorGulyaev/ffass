<?php

	class Pixxy_Csvimport_Model_Resource_Reindex_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{
		
		public function _construct(){
			$this->_init('pixxy_csvimport/reindex');
		}
	    
	}
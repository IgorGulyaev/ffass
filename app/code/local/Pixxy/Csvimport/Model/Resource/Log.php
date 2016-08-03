<?php

	class Pixxy_Csvimport_Model_Resource_Log extends Mage_Core_Model_Resource_Db_Abstract{
		
		protected function _construct(){
			$this->_init('pixxy_csvimport/log', 'id');
		}
		
		public function getNumOfErrors(){
			return $this->_getWriteAdapter()->select("count(log.id)")
					->from(array('log' => $this->getTable('pixxy_csvimport/log')), array('*'))
					->group('log.file_id');
		}
		
	}
<?php

	class Pixxy_Csvimport_Model_Tmppositions extends Mage_Core_Model_Abstract{
		
		public function _construct(){
			$this->_init('pixxy_csvimport/tmppositions');
		}
		
		/**
		 * Delete old and add new positions 
		 */
		public function addNewPositions($positions, $mg_attribute_array){
			$old_positions = $this->getCollection();
			if(count($old_positions)){
				foreach ($old_positions as $old_position){
					$old_position->delete();
				}
			}
			$br = 0;
			if(count($positions)){
				foreach ($positions as $new_position){
					$position = Mage::getModel('pixxy_csvimport/tmppositions');
					$position->setPosition($new_position);
					$position->setMgAttribute($mg_attribute_array[$br]);
					$position->save();
					$br++;
				}
			}
		}
		
	}
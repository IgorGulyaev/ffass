<?php
	class Pixxy_Csvimport_Model_Grid_Yesno extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
	 
		public function render(Varien_Object $row){
			$value =  (int)$row->getData($this->getColumn()->getIndex());
			if($value == 0){
				return '<span class="grid-severity-minor"><span>No</span></span>';
			}
			else{
				return '<span class="grid-severity-notice"><span>Yes</span></span>';
			}
		}
	 
	}

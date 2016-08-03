<?php
	class Pixxy_Csvimport_Model_Grid_Ew extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
	 
		public function render(Varien_Object $row){
			$value =  (int)$row->getData($this->getColumn()->getIndex());
			if($value > 0){
				return '<span style="color:red;">'.$value.'</span>';
			}
			else{
				return '<span style="color:green;">'.$value.'</span>';
			}
		}
	 
	}

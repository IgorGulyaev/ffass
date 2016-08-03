<?php
	class Pixxy_Csvimport_Model_Grid_Nor extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
	 
		public function render(Varien_Object $row){
			$value =  $row->getData($this->getColumn()->getIndex());
			if($value == '999999'){
				return '<span style="color:red;">Fatal error reading file</span>';
			}
			else if($value == '0'){
				return '<span style="color:red;">Unknown</span>';
			}
			else{
				return '<span style="color:green;">'.$value.'</span>';
			}
		}
	 
	}

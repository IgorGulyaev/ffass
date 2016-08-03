<?php

	class Pixxy_Csvimport_Block_Adminhtml_Mapping_New extends Mage_Adminhtml_Block_Widget_Form_Container{
		
		protected $_blockGroup = 'csvimport';
		protected $_controller = 'adminhtml_mapping_new';
		protected $_mode = 'edit';
		
		public function getHeaderText(){
			return Mage::helper('csvimport')->__('New Mapping Profile');
		}
		
	}
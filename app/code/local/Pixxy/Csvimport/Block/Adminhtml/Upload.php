<?php

	class Pixxy_Csvimport_Block_Adminhtml_Upload extends Mage_Adminhtml_Block_Widget_Form_Container{
		
		protected $_blockGroup = 'csvimport';
		protected $_controller = 'adminhtml_upload';
		protected $_mode = 'edit';
		
		public function __construct(){
			parent::__construct();
			$this->_updateButton('save', 'label','Upload');
		}
		
		public function getHeaderText(){
			return Mage::helper('csvimport')->__('Upload file');
		}
		
	}
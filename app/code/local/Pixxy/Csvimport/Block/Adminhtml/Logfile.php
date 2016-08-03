<?php

	class Pixxy_Csvimport_Block_Adminhtml_Logfile extends Mage_Adminhtml_Block_Widget_Grid_Container{
		
		protected $_headerText = 'CSV Import Log File';
		protected $_controller = 'adminhtml_logfile';
		protected $_blockGroup = 'csvimport';
		
		public function __construct(){
			parent::__construct();
			$this->_removeButton('add');	
		}
		
	}
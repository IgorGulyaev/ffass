<?php

	class Pixxy_Csvimport_Block_Adminhtml_Mapping extends Mage_Adminhtml_Block_Widget_Form_Container{
		
		protected $_blockGroup = 'csvimport';
		protected $_controller = 'adminhtml_mapping';
		protected $_mode = 'edit';
		
		public function __construct(){
			parent::__construct();
			$this->_addButton('new', array(
				'label' => 'New Mapping Profile',
				'onclick' => 'newMappingProfile()',
				'class' => 'save',
			));
			$this->_addButton('duplicate', array(
				'label' => 'Duplicate Mapping Profile',
				'onclick' => 'duplicateMappingProfile()',
				'class' => 'add',
			));
			$this->_addButton('delete', array(
				'label' => 'Delete Mapping Profile',
				'onclick' => 'deleteMappingProfile()',
				'class' => 'delete',
			));
			$this->_formScripts[] = " function newMappingProfile(){
            	window.location.href = '".$this->getUrl('*/*/new')."'
        	}
        	function deleteMappingProfile(){
        		if(confirm('Delete Mapping Profile - ' + $('profile_name')[$('profile_name').selectedIndex].text + ' ?')){
        			window.location.href ='".$this->getUrl('*/*/delete')."profile_name/'+$('profile_name')[$('profile_name').selectedIndex].text;
        		}
        	}
        	function duplicateMappingProfile(){
        		var text = prompt('Enter Mapping Profile name','');
        		if(text != null && text != ''){
        			window.location.href ='".$this->getUrl('*/*/duplicate')."profile_name/'+$('profile_name')[$('profile_name').selectedIndex].text+'/copy_name/'+text;
        		}
        	}
        	";
			$this->_updateButton('save', 'label','Save Mapping');
		}
		
		public function getHeaderText(){
			return Mage::helper('csvimport')->__('Attribute Mapping');
		}
		
	}
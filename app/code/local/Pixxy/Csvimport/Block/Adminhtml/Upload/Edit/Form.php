<?php

	class Pixxy_Csvimport_Block_Adminhtml_Upload_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{
		
		protected function _prepareForm(){
			$form = new Varien_Data_Form(array(
	            'id' => 'edit_form',
	            'action' => $this->getUrl('*/*/save'),
	            'method' => 'post',
				'enctype'=> 'multipart/form-data',
	        ));
	 
	        $form->setUseContainer(true);
	 
	        $this->setForm($form);
	 
	        $fieldset = $form->addFieldset('upload_form', array(
	            'legend' => Mage::helper('csvimport')->__('Upload file')
	        ));
	 
	        $fieldset->addField('file', 'file', array(
	            'label' => Mage::helper('csvimport')->__('Select file'),
	            'class' => 'required-entry',
	            'required' => true,
	        	'name' => 'file',
	            'note' => Mage::helper('csvimport')->__('.csv or .txt format (semicolon, comma or tab separated)'),
	        ));
	        
	         $fieldset->addField('profile_name', 'select', array(
	            'label' => Mage::helper('csvimport')->__('Select profile'),
	            'class' => 'required-entry',
	            'required' => true,
	        	'name' => 'profile_name',
	        	'values' => Mage::getModel('pixxy_csvimport/mappingprofile')->getCollection()->addFieldToFilter('hide', array('eq'=>'0'))->toOptionArray(),
	         ));
	        
	        return parent::_prepareForm();
		}
		
	}
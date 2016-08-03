<?php

	class Pixxy_Csvimport_Block_Adminhtml_Mapping_New_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{
		
		protected function _prepareForm(){

			$form = new Varien_Data_Form(array(
	            'id' => 'edit_form',
	            'action' => $this->getUrl('*/*/saveMappingProfile'),
	            'method' => 'post',
				'enctype'=> 'multipart/form-data',
	        ));
	        
	        $form->setUseContainer(true);
		
			$this->setForm($form);
	        
	        $fieldset_attributes = $form->addFieldset('attributes', array(
	            'legend' => Mage::helper('csvimport')->__('Attribute Mapping Profile')
	        ));
	        
        	$fieldset_attributes->addField('profile_name', 'text', array(
	            'label' => 'Enter profile name',
        		'class' => 'required-entry',
	            'required' => true,
	        	'name' => 'profile_name',
        	));
	        
	        return parent::_prepareForm();
		}
		
	}
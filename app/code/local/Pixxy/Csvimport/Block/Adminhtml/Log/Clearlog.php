<?php

	class Pixxy_Csvimport_Block_Adminhtml_Log_Clearlog extends Mage_Adminhtml_Block_System_Config_Form_Field{
		
		protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element){
			$this->setElement($element);
			$url = $this->getUrl('*/log/clearlog');
			
			$html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('scalable')
                    ->setLabel('Clear Log')
                    ->setOnClick("setLocation('$url')")
                    ->toHtml();

        	return $html;
		}
		
	}
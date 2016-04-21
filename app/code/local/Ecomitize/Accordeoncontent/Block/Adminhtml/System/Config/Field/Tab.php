<?php

class Ecomitize_Accordeoncontent_Block_Adminhtml_System_Config_Field_Tab extends Mage_Core_Block_Html_Select
{

    public function _toHtml()
    {
        $options = Mage::getModel('ecomitize_accordeoncontent/options')->toOptionArray();

        foreach ($options as $option) {
            $option['label'] = str_replace("'", "", $option['label']);
            $this->addOption($option['value'], $option['label']);
        }
        return parent::_toHtml();
    }

    public function setInputName($value)
    {
        return $this->setName($value);
    }

}
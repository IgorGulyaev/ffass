<?php

class Zen_Pagesaccordeon_Block_Adminhtml_System_Config_Field_Type extends Mage_Core_Block_Html_Select
{

    public function _toHtml()
    {
        $options = Mage::getModel('zen_pagesaccordeon/typelink')->toOptionArray();

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
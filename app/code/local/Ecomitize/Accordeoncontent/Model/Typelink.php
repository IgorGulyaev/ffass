<?php

class Ecomitize_Accordeoncontent_Model_Typelink
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'tabs','label'=>Mage::helper('ecomitize_accordeoncontent')->__('Tab')),
            array('value' => 'link','label'=>Mage::helper('ecomitize_accordeoncontent')->__('Link')),
        );
    }


}
<?php

class Ecomitize_Accordeoncontent_Model_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'tab','label'=>Mage::helper('ecomitize_accordeoncontent')->__('Tab')),
            array('value' => 'accordeon','label'=>Mage::helper('ecomitize_accordeoncontent')->__('Accordeon')),
        );
    }


}
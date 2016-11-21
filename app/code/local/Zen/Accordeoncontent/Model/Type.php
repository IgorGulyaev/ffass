<?php

class Zen_Accordeoncontent_Model_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'tab','label'=>Mage::helper('zen_accordeoncontent')->__('Tab')),
            array('value' => 'accordeon','label'=>Mage::helper('zen_accordeoncontent')->__('Accordeon')),
        );
    }


}
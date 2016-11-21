<?php

class Zen_Accordeoncontent_Model_Typelink
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'tabs','label'=>Mage::helper('zen_accordeoncontent')->__('Tab')),
            array('value' => 'link','label'=>Mage::helper('zen_accordeoncontent')->__('Link')),
        );
    }


}
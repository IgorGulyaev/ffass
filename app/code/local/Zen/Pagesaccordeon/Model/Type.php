<?php

class Zen_Pagesaccordeon_Model_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'tab','label'=>Mage::helper('zen_pagesaccordeon')->__('Tab')),
            array('value' => 'accordeon','label'=>Mage::helper('zen_pagesaccordeon')->__('Accordeon')),
        );
    }


}
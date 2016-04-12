<?php

class Ecomitize_Pagesaccordeon_Model_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'tab','label'=>Mage::helper('ecomitize_pagesaccordeon')->__('Tab')),
            array('value' => 'accordeon','label'=>Mage::helper('ecomitize_pagesaccordeon')->__('Accordeon')),
        );
    }


}
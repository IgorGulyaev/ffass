<?php

class Ecomitize_Pagesaccordeon_Model_Typelink
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'tabs','label'=>Mage::helper('ecomitize_pagesaccordeon')->__('Tab')),
            array('value' => 'link','label'=>Mage::helper('ecomitize_pagesaccordeon')->__('Link')),
        );
    }


}
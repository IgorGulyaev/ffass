<?php

class Ecomitize_Pagesaccordeon_Model_Config
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'inner','label'=>Mage::helper('ecomitize_pagesaccordeon')->__('Inner')),
            array('value' => 'outer','label'=>Mage::helper('ecomitize_pagesaccordeon')->__('Outer')),
            array('value' => 'cmspage','label'=>Mage::helper('ecomitize_pagesaccordeon')->__('CMS page')),
        );
    }


}
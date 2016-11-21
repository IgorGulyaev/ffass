<?php

class Zen_Pagesaccordeon_Model_Config
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'inner','label'=>Mage::helper('zen_pagesaccordeon')->__('Inner')),
            array('value' => 'outer','label'=>Mage::helper('zen_pagesaccordeon')->__('Outer')),
            array('value' => 'cmspage','label'=>Mage::helper('zen_pagesaccordeon')->__('CMS page')),
        );
    }


}
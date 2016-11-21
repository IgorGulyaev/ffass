<?php

class Zen_Pagesaccordeon_Model_Typelink
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'tabs','label'=>Mage::helper('zen_pagesaccordeon')->__('Tab')),
            array('value' => 'link','label'=>Mage::helper('zen_pagesaccordeon')->__('Link')),
        );
    }


}
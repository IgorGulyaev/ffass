<?php

class Ecomitize_All_Model_Renderer_Minicart extends Varien_Object
    implements AW_Ajaxcartpro_Model_Renderer_Interface
{
    const BLOCK_NAME = 'minicart_head';

    public function renderFromLayout($layout)
    {
        $block = $layout->getBlock(self::BLOCK_NAME);
        if (!$block) {
            return null;
        }
        return $block->toHtml();
    }
}
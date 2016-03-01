<?php

class Ecomitize_All_Model_Renderer_Cartlink extends Varien_Object
    implements AW_Ajaxcartpro_Model_Renderer_Interface
{
    const BLOCK_NAME = 'cart.link';

    public function renderFromLayout($layout)
    {
        $block = $layout->getBlock(self::BLOCK_NAME);
        if (!$block) {
            return null;
        }
        return $block->toHtml();
    }
}
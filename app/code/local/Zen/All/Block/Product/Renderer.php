<?php
/**
 * Product renderer
 *
 * Class Zen_All_Block_Product_Renderer
 */
class Zen_All_Block_Product_Renderer extends Mage_Catalog_Block_Product_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('catalog/product/general_item_view.phtml');
    }
}
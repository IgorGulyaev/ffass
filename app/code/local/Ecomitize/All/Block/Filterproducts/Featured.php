<?php
/**
 * Featured products block
 * Can be used on category page too (it will return products for current category and all their subcategories).
 */
class Ecomitize_All_Block_Filterproducts_Featured extends Mage_Catalog_Block_Product_List
{
    /**
     * Featured products list
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
        $storeId  = Mage::app()->getStore()->getId();
        $products = Mage::getResourceModel('catalog/product_collection')
            ->setStoreId($storeId)
            ->addAttributeToFilter('featured', array('eq' => '1'))
            ->addAttributeToSelect('image')
            ->addAttributeToSelect('name')
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->setPageSize(5)
            ->addAttributeToSort('position', 'desc');

        if ($curCat = Mage::registry('current_category')) {
            $products->addCategoryFilter($curCat)
                ->addUrlRewrite($curCat->getId());
        }

        Mage::getSingleton('catalog/product_status')
            ->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')
            ->addVisibleInCatalogFilterToCollection($products);

        $this->_productCollection = $products;
        return $this->_productCollection;
    }
}

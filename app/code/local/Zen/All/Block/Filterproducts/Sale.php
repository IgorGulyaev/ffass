<?php
/**
 * Block for on sale products
 *
 */
class Zen_All_Block_Filterproducts_Sale extends Mage_Catalog_Block_Product_List
{
    protected function _getProductCollection()
    {
        $category = Mage::getResourceModel('catalog/category_collection')
            ->addFieldToFilter('url_key', 'onsale')
            ->getFirstItem();
        $storeId  = Mage::app()->getStore()->getId();
        $products = Mage::getResourceModel('catalog/product_collection')
            ->setStoreId($storeId)
            ->addCategoryFilter($category)
            ->addAttributeToSelect('image')
            ->addAttributeToSelect('name')
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->setPageSize(5)
            ->addAttributeToSort('position', 'desc');

        Mage::getSingleton('catalog/product_status')
            ->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')
            ->addVisibleInCatalogFilterToCollection($products);

        $this->_productCollection = $products;
        return $this->_productCollection;
    }

    function get_prod_count()
    {
        //unset any saved limits
        Mage::getSingleton('catalog/session')->unsLimitPage();
        return (isset($_REQUEST['limit'])) ? intval($_REQUEST['limit']) : 9;
    }

    function get_cur_page()
    {
        return (isset($_REQUEST['p'])) ? intval($_REQUEST['p']) : 1;
    }

    function get_order()
    {
        return (isset($_REQUEST['order'])) ? ($_REQUEST['order']) : 'entity_id';
    }

    function get_order_dir()
    {
        return (isset($_REQUEST['dir'])) ? ($_REQUEST['dir']) : 'desc';
    }
}

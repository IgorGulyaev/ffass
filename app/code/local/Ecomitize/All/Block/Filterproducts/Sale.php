<?php
/**
 * Block for on sale products
 *
 */
class Ecomitize_All_Block_Filterproducts_Sale extends Mage_Catalog_Block_Product_List
{
    protected function _getProductCollection()
    {
        $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $tomorrow = mktime(0, 0, 0, date('m'), date('d')+1, date('y'));
        $dateTomorrow = date('m/d/y', $tomorrow);
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('image');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToSort('entity_id', 'desc') //This will show the latest products first
            ->addAttributeToFilter('special_price', array('gt'=> -1))
            ->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
            ->addAttributeToFilter('special_to_date', array('or'=> array(0 => array('date' => true, 'from' => $dateTomorrow), 1 => array('is' => new Zend_Db_Expr('null')))), 'left')
            ->setPageSize($this->get_prod_count())
            ->setOrder($this->get_order(), $this->get_order_dir())
            ->setCurPage($this->get_cur_page());
        $this->_productCollection = $collection;
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

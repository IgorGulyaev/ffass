<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Prutyan
 * Date: 5/30/2016
 * Time: 12:07 PM
 */

class Ecomitize_Footernav_Model_Observer{

    public function assignProductsToFeaturedCategories(){

        $featuredCollection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('featured', array('eq' => '1'))
            ->getAllIds();

        $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $tomorrow = mktime(0, 0, 0, date('m'), date('d')+1, date('y'));
        $dateTomorrow = date('m/d/y', $tomorrow);

        $onsaleCollection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('special_price', array('gt'=> -1))
            ->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
            ->addAttributeToFilter('special_to_date', array('or'=> array(0 => array('date' => true, 'from' => $dateTomorrow), 1 => array('is' => new Zend_Db_Expr('null')))), 'left')
            ->getAllIds();

        $block = Mage::getBlockSingleton('reports/product_viewed');
        $clearanceCollection = $block->getItemsCollection()->getAllIds();

        $this->assignProducts('featured',$featuredCollection);
        $this->assignProducts('onsale',$onsaleCollection);
        //$this->assignProducts('clearance',$clearanceCollection);

    }

    public function assignProducts($categoryUrlKey,$productIds){

        $categoryId = Mage::getResourceModel('catalog/category_collection')
            ->addFieldToFilter('url_key', $categoryUrlKey)
            ->getFirstItem()
            ->getId();

        $catApi = Mage::getSingleton('catalog/category_api');

        $products = $catApi->assignedProducts($categoryId);
        $oldIds = array();
        foreach ($products as $product) {
            if(!in_array($product['product_id'],$productIds)){
                $catApi->removeProduct($categoryId,$product['product_id']);
            }
            $oldIds[] = $product['product_id'];
        }
        foreach($productIds as $productId){
            if(!in_array($productId,$oldIds)) {
                $catApi->assignProduct($categoryId, $productId);
            }
        }
    }
}
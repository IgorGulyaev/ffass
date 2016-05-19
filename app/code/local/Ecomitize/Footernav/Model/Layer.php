<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Prutyan
 * Date: 12/22/2015
 * Time: 1:07 PM
 */

class Ecomitize_Footernav_Model_Layer extends Mage_Catalog_Model_Layer{

    /**
     * Retrieve current layer product collection
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    public function getProductCollection()
    {
        $route = Mage::app()->getRequest()->getRouteName();

        if($route == 'offers'){
            $params = Mage::app()->getRequest()->getParams();
            $key = array_keys($params);
            if (isset($this->_productCollections[$this->getCurrentCategory()->getId()])) {
                $collection = $this->_productCollections[$this->getCurrentCategory()->getId()];
            } else {

                if($params['featured']){
                    $collection = Mage::getModel('catalog/product')->getCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('featured', array('eq' => '1'));
                    $this->prepareProductCollection($collection);
                    $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
                }
                if($params['clearance']){
                    $block = Mage::getBlockSingleton('reports/product_viewed');
                    $collection = $block->getItemsCollection();
                    $this->prepareProductCollection($collection);
                    $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
                }
                if($params['onsale']){
                    $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
                    $tomorrow = mktime(0, 0, 0, date('m'), date('d')+1, date('y'));
                    $dateTomorrow = date('m/d/y', $tomorrow);

                    $collection = Mage::getModel('catalog/product')->getCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('special_price', array('gt'=> -1))
                        ->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
                        ->addAttributeToFilter('special_to_date', array('or'=> array(0 => array('date' => true, 'from' => $dateTomorrow), 1 => array('is' => new Zend_Db_Expr('null')))), 'left');

                    $this->prepareProductCollection($collection);
                    $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
                }

            }

            return $collection;
        }
        else{
            return parent::getProductCollection();
        }
    }
}
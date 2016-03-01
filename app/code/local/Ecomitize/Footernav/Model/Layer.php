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
                //$collection = $this->getCurrentCategory()->getProductCollection();

                $collection = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter($key[0],array('eq' => $params[$key[0]]));
                $this->prepareProductCollection($collection);
                $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
            }

            return $collection;
        }
        else{
            return parent::getProductCollection();
        }
    }
}
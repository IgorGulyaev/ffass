<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Prutyan
 * Date: 12/21/2015
 * Time: 3:51 PM
 */

class Ecomitize_Footernav_IndexController extends Mage_Core_Controller_Front_Action{

    public function indexAction(){

        $this->loadLayout();
        $title = $this->setCustomTitle();
        $this->getLayout()->getBlock('head')->setTitle($this->__($title));
        $this->renderLayout();
    }

    public function setCustomTitle(){
        $params = $this->getRequest()->getParams();
        $option = 'BestDressedTot';
        if($params['featured'] == 1){
            $option = 'Customer`s Favorites' ;
        }
        if($params['onsale'] == 1){
            $option = 'New Arrivals';
        }
        if($params['clearance'] == 1){
            $option = 'Recently Viewed';
        }
        if($params['related'] == 1){
            $product = Mage::getModel('catalog/product')->load($params['product']);
            if($product->getId()){
                $option = 'Related Products of ' . $product->getName();
            }
        }
        return $option;
    }
}
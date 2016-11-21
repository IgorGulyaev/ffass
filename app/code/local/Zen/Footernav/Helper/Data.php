<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Prutyan
 * Date: 5/18/2016
 * Time: 5:25 PM
 */


class Zen_Footernav_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getBreadCrumbs($params)
    {

        $link = '';
        $label = '';
        $option = '';
        if($params['featured'] == 1){
            $label = 'Customer`s Favorites';
            $option = 'Customer`s Favorites';
        }
        if($params['onsale'] == 1){
            $label = 'New Arrivals';
            $option = 'New Arrivals';
        }
        if($params['clearance'] == 1){
            $label = 'Recently Viewed';
            $option = 'Recently Viewed';
        }
        if($params['related'] == 1){
            $product = Mage::getModel('catalog/product')->load($params['product']);
            if($product->getId()){
                $option = 'Related Products of ' . $product->getName();
                $label = 'Related Products of ' . $product->getName();
            }
        }

        $breadcrumbs = Mage::app()->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb('home', array('label' => Mage::helper('cms')->__('Home'), 'title' => Mage::helper('cms')->__('Home Page'), 'link' => Mage::getBaseUrl()));

        if ($link != '' && $label != '') {
            $breadcrumbs->addCrumb('type', array('label' => $label, 'title' => $label, 'link' => $link));
        }

        $breadcrumbs->addCrumb('manufacturer', array('label' => $option, 'title' => $option));
        $data = array('breadcrumbs' => $breadcrumbs, 'option' => $option);
        return $data;

    }


}
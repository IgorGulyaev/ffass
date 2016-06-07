<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento community edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento community edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Mobiletracking
 * @version    1.1.1
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Mobiletracking_Block_Tracking extends Mage_Core_Block_Template {
     
    public function getStoreConfig($info, $path = AW_Mobiletracking_Helper_Data::GENERAL_TAB, $root= AW_Mobiletracking_Helper_Data::MODULE_NAME) {
        
        return Mage::helper('mobiletracking')->getStoreConfig($info, $path, $root);      
        
    }
    
    public function getLogoUrl($path) {        
        
        return Mage::helper('mobiletracking')->getLogoUrl($path);  
        
    }
    
    public function getTrackingInfo() {        
        
        // return Mage::getBlockSingleton('mobiletracking/trackinginfo')->setTemplate('shipping/tracking/popup.phtml')->renderView();
        return Mage::getBlockSingleton('mobiletracking/trackinginfo')->setTemplate('aw_mobiletracking/shipping/popup.phtml')->renderView();
        
    }
    
    public function getAwMobileTracker() {
         
        return $this;
        
    }

    public function getCustomer()
    {
        if(Mage::getSingleton('customer/session')->isLoggedIn()) {
            return Mage::getSingleton('customer/session')->getCustomer();
        }
        return false;
    }

    public function getCustomerOrdersSelectElement($customer)
    {
        if (!$customer) {
            return '';
        }

        $orders = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('customer_id', $customer->getId());

        foreach ($orders as $order) {
            $options[] = array('value'  => $order->getIncrementId(), 'label' => $order->getIncrementId());
        }

        if (!$orders->count()) {
            return $this->__('There is no orders available for this account.');
        }

        return $this->_getSelectBlock()
            ->setName(AW_Mobiletracking_Helper_Data::MOBILETRACKING_ORDER_NUMBER)
            ->setId('aw_mobiletracking_customer_order_number')
            ->setOptions($options)
            ->getHtml();
    }

    protected function _getSelectBlock()
    {
        $block = $this->getData('_select_block');
        if (is_null($block)) {
            $block = $this->getLayout()->createBlock('core/html_select');
            $this->setData('_select_block', $block);
        }
        return $block;
    }
}
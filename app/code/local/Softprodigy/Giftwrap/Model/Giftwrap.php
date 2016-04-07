<?php
 /**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Softprodigy
 * @package     Softprodigy_Giftwrap
 * @copyright   Copyright (c) 2013 Softprodigy System Solutions Pvt. Ltd (http://www.softprodigy.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Softprodigy_Giftwrap_Model_Giftwrap extends Mage_Core_Model_Abstract
{
	const GIFTWRAP_AMOUNT = 25;
	
    public function _construct()
    {
        parent::_construct();
        $this->_init('giftwrap/giftwrap_product');
    }
    
    public static function getGiftwrap()
    {
		$total_price = 0;
		$_giftwraps = Mage::getSingleton('core/session')->getGiftWraps(); 
		$_cart = Mage::getModel('checkout/cart')->getQuote();
		foreach ($_cart->getAllItems() as $item) {
			$productId = $item->getProductId();
			if(isset($_giftwraps[$productId])) {
				$new_giftwrap[$productId] = $_giftwraps[$productId]; 
			}
		}
		Mage::getSingleton('core/session')->setGiftWraps($new_giftwrap); 
		$giftwraps = $new_giftwrap;
		//$giftwraps = Mage::getSingleton('core/session')->getGiftWraps();
		foreach($giftwraps as $giftwrap) {
			$gift = Mage::getModel('giftwrap/giftwrap')->load($giftwrap);
			$total_price += $gift->getGiftwrapPrice();
		}
        //return self::GIFTWRAP_AMOUNT;
        return $total_price;
    }
    
    public static function canApply($address)
    {
        // Put here your business logic to check if fee should be applied or not

        // Example of data retrieved :
        // $address->getShippingMethod(); > flatrate_flatrate
        // $address->getQuote()->getPayment()->getMethod(); > checkmo
        // $address->getCountryId(); > US
        // $address->getQuote()->getCouponCode(); > COUPONCODE

        return true;
    }
}

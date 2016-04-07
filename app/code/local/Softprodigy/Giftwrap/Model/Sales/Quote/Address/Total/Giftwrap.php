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

class Softprodigy_Giftwrap_Model_Sales_Quote_Address_Total_Giftwrap extends Mage_Sales_Model_Quote_Address_Total_Abstract
{

    protected $_code = 'giftwrap';

    /**
     * Collect fee address amount
     *
     * @param Mage_Sales_Model_Quote_Address $address
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
		$giftwraps = Mage::getSingleton('core/session')->getGiftWraps();
		if(!empty($giftwraps)) {
			parent::collect($address);

			$this->_setAmount(0);
			$this->_setBaseAmount(0);

			$items = $this->_getAddressItems($address);
			if (!count($items)) {
				return $this;
			}

			$quote = $address->getQuote();

			if (Softprodigy_Giftwrap_Model_Giftwrap::canApply($address)) {
				$exist_amount = $quote->getGiftwrapAmount();
				$giftwrap = Softprodigy_Giftwrap_Model_Giftwrap::getGiftwrap();
				$balance = $giftwrap - $exist_amount;

				$address->setGiftwrapAmount($balance);
				$address->setBaseGiftwrapAmount($balance);

				$quote->setGiftwrapAmount($balance);

				$address->setGrandTotal($address->getGrandTotal() + $address->getGiftwrapAmount());
				$address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseGiftwrapAmount());
			}

			return $this;
		}
    }

    /**
     * Add fee information to address
     *
     * @param Mage_Sales_Model_Quote_Address $address
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
		$giftwraps = Mage::getSingleton('core/session')->getGiftWraps();
		$wraps = array();
		foreach($giftwraps as $gifts) {
			$gift = Mage::getModel('giftwrap/giftwrap')->load($gifts);
			$wraps[] = $gift->getGiftwrapName();
		}
		if(!empty($giftwraps)) {
			$amount = $address->getGiftwrapAmount();
			$address->addTotal(array(
				'code' => $this->getCode(),
				'title' => Mage::helper('giftwrap')->__('Giftwrap ('.implode(", ",$wraps).')'),
				'value' => $amount
			));
			return $this;
		}
    }

}

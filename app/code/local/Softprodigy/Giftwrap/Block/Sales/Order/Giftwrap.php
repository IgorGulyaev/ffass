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

class Softprodigy_Giftwrap_Block_Sales_Order_Giftwrap extends Mage_Core_Block_Template
{

    /**
     * Get order store object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * Get totals source object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * Initialize fee totals
     *
     */
    public function initTotals()
    {
        if ((float) $this->getOrder()->getBaseGiftwrapAmount()) {
            $source = $this->getSource();
            $value  = $source->getGiftwrapAmount();
			$giftwraps = Mage::getSingleton('core/session')->getGiftWraps();
			if(!empty($giftwraps)) {
				$this->getParentBlock()->addTotal(new Varien_Object(array(
					'code'   => 'giftwrap',
					'strong' => false,
					'label'  => Mage::helper('giftwrap')->__('Giftwrap'),
					'value'  => $value
				)));
			}
        }

        return $this;
    }
}

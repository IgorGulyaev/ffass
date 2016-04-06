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

class Softprodigy_Giftwrap_Model_Sales_Order_Total_Creditmemo_Giftwrap extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{

    /**
     * Collect credit memo total
     *
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     */
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();
		
		$giftwrapAmountLeft1 = $order->getGiftwrapAmount() - $order->getGiftwrapAmountInvoiced();
        $basegiftwrapAmountLeft1 = $order->getBaseGiftwrapAmount() - $order->getBaseGiftwrapAmountInvoiced();
        
        if($order->getGiftwrapAmountInvoiced() > 0) {

            $giftwrapAmountLeft = $order->getGiftwrapAmountInvoiced() - $order->getGiftwrapAmountRefunded();
            $basegiftwrapAmountLeft = $order->getBaseGiftwrapAmountInvoiced() - $order->getBaseGiftwrapAmountRefunded();

            if ($basegiftwrapAmountLeft > 0) {
                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $giftwrapAmountLeft);
                $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basegiftwrapAmountLeft);
                $creditmemo->setGiftwrapAmount($giftwrapAmountLeft);
                $creditmemo->setBaseGiftwrapAmount($basegiftwrapAmountLeft);
            }

        } else {

            $giftwrapAmount = $order->getGiftwrapAmountInvoiced();
            $basegiftwrapAmount = $order->getBaseGiftwrapAmountInvoiced();

            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $giftwrapAmountLeft1);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basegiftwrapAmountLeft1);
            $creditmemo->setGiftwrapAmount($giftwrapAmountLeft1);
            $creditmemo->setBaseGiftwrapAmount($basegiftwrapAmountLeft1);

        }

        return $this;
    }

}

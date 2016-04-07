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

class Softprodigy_Giftwrap_Model_Sales_Order_Total_Invoice_Giftwrap extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{

    /**
     * Collect invoice total
     *
     * @param Mage_Sales_Model_Order_Invoice $invoice
     */
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order = $invoice->getOrder();

        $giftwrapAmountLeft = $order->getGiftwrapAmount() - $order->getGiftwrapAmountInvoiced();
        $basegiftwrapAmountLeft = $order->getBaseGiftwrapAmount() - $order->getBaseGiftwrapAmountInvoiced();

        if (abs($baseGiftwrapAmountLeft) < $invoice->getBaseGrandTotal()) {
            $invoice->setGrandTotal($invoice->getGrandTotal() + $giftwrapAmountLeft);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseGiftwrapAmountLeft);
        } else {
            $giftwrapAmountLeft = $invoice->getGrandTotal() * -1;
            $baseGiftwrapAmountLeft = $invoice->getBaseGrandTotal() * -1;

            $invoice->setGrandTotal(0);
            $invoice->setBaseGrandTotal(0);
        }

        $invoice->setGiftwrapAmount($giftwrapAmountLeft);
        $invoice->setBaseGiftwrapAmount($baseGiftwrapAmountLeft);

        return $this;
    }

}

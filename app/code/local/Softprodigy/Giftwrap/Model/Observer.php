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

class Softprodigy_Giftwrap_Model_Observer
{

    /**
     * Set fee amount invoiced to the order
     *
     * @param Varien_Event_Observer $observer
     */
    public function invoiceSaveAfter(Varien_Event_Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();

        if ($invoice->getBaseGiftwrapAmount()) {
            $order = $invoice->getOrder();
            $order->setGiftwrapAmountInvoiced($order->getGiftwrapAmountInvoiced() + $invoice->getGiftwrapAmount());
            $order->setBaseGiftwrapAmountInvoiced($order->getBaseGiftwrapAmountInvoiced() + $invoice->getBaseGiftwrapAmount());
        }

        return $this;
    }

    /**
     * Set fee amount refunded to the order
     *
     * @param Varien_Event_Observer $observer
     */
    public function creditmemoSaveAfter(Varien_Event_Observer $observer)
    {
        $creditmemo = $observer->getEvent()->getCreditmemo();

        if ($creditmemo->getGiftwrapAmount()) {
            $order = $creditmemo->getOrder();
            $order->setGiftwrapAmountRefunded($order->getGiftwrapAmountRefunded() + $creditmemo->getGiftwrapAmount());
            $order->setBaseGiftwrapAmountRefunded($order->getBaseGiftwrapAmountRefunded() + $creditmemo->getBaseGiftwrapAmount());
        }

        return $this;
    }

    /**
     * Update PayPal Total
     *
     * @param Varien_Event_Observer $observer
     */
    public function updatePaypalTotal(Varien_Event_Observer $observer)
    {
        $cart = $observer->getEvent()->getPaypalCart();

        $cart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_SUBTOTAL, $cart->getSalesEntity()->getGiftwrapAmount());

        return $this;
    }

}

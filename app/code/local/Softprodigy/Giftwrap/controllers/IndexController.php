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
 
class Softprodigy_Giftwrap_IndexController extends Mage_Core_Controller_Front_Action
{

	public function indexAction() {
		$existing_wraps = Mage::getSingleton('core/session')->getGiftWraps();
		$data = Mage::app()->getRequest()->getPost();
		if($data['giftwraps'] != "") {
			if($data['product_id'] != "") {
				$existing_wraps[$data['product_id']] = $data['giftwraps'];
				Mage::getSingleton('core/session')->setGiftWraps($existing_wraps);
				Mage::getSingleton('core/session')->addSuccess('Gift wrap added successfully!');
				$this->_redirect("checkout/cart");
			} else {
				Mage::getSingleton('core/session')->addError('Please try again!');
				$this->_redirect("checkout/cart");
			}
		} else {
			Mage::getSingleton('core/session')->addError('Please select atleast one gift wrap item!');
			$this->_redirect("checkout/cart");
		}
	}
	
	public function removeAction() {
		$id = $this->getRequest()->getParam('id');
		if($id) {
			$existing_wraps = Mage::getSingleton('core/session')->getGiftWraps();
			unset($existing_wraps[$id]);
			Mage::getSingleton('core/session')->setGiftWraps($existing_wraps);
			Mage::getSingleton('core/session')->addSuccess('Gift wrap removed successfully!');
			$this->_redirect("checkout/cart");
		} else {
			Mage::getSingleton('core/session')->addError('Please try again!');
			$this->_redirect("checkout/cart");
		}
	}
}

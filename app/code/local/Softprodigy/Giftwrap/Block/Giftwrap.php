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
 
class Softprodigy_Giftwrap_Block_Giftwrap extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getGiftwrap()     
     { 
        if (!$this->hasData('giftwrap')) {
            $this->setData('giftwrap', Mage::registry('giftwrap'));
        }
        return $this->getData('giftwrap');
        
    }
}

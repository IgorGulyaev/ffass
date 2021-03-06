<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Customerattr
 */

/**
 * Form select element
 *
 * @category    Varien
 * @package     Varien_Data
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Amasty_Customerattr_Block_Data_Form_Element_Boolean
    extends Varien_Data_Form_Element_Select
{


    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        //   $this->_renderer = $this->getLayout()->createBlock("amcustomerattr/adminhtml_customer_attribute_grid_renderer_type");
    }

    public function getElementHtml()
    {
        $hlp = Mage::helper('amcustomerattr');
        $this->setValues(
            array(
                0 => $hlp->__('No'),
                1 => $hlp->__('Yes'),
            ));
        return parent::getElementHtml();
    }


}
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
 
class Softprodigy_Giftwrap_Block_Adminhtml_Giftwrap_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('giftwrap_form', array('legend'=>Mage::helper('giftwrap')->__("Gift Wrap Information")));
     
      $fieldset->addField('giftwrap_name', 'text', array(
          'label'     => Mage::helper('giftwrap')->__('Gift Wrap Name'),
          'required'  => true,
          'name'      => 'giftwrap_name',
      ));
   
	  $fieldset->addField('giftwrap_image', 'image', array(
          'label'     => Mage::helper('giftwrap')->__('Gift Wrap Image'),
          'name'      => 'giftwrap_image',
          'required'  => true,
      ));
       
       $fieldset->addField('giftwrap_price', 'text', array(
          'label'     => Mage::helper('giftwrap')->__('Price'),
          'required'  => true,
          'name'      => 'giftwrap_price',
      ));
      
      
      $fieldset->addField('giftwrap_status', 'select', array(
          'label'     => Mage::helper('giftwrap')->__('Status'),
          'name'      => 'giftwrap_status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('giftwrap')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('giftwrap')->__('Disabled'),
              ),
          ),
      ));
      
      if ( Mage::getSingleton('adminhtml/session')->getGiftwrapData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getGiftwrapData());
          Mage::getSingleton('adminhtml/session')->setGiftwrapData(null);
      } elseif ( Mage::registry('giftwrap_data') ) {
          $form->setValues(Mage::registry('giftwrap_data')->getData());
      }
      return parent::_prepareForm();
  }
}

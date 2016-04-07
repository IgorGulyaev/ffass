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
 
class Softprodigy_Giftwrap_Block_Adminhtml_Giftwrap_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('giftwrapGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getResourceModel('giftwrap/giftwrap_product_collection')->addAttributeToSelect('*');
      
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _getStore()
  {
	$storeId = (int) $this->getRequest()->getParam('store', 0);
	return Mage::app()->getStore($storeId);
  }
  protected function _prepareColumns()
  {
      $this->addColumn('entity_id', array(
          'header'    => Mage::helper('giftwrap')->__('ID'),
          'width' => '50px',
          'type'  => 'number',
          'index'     => 'entity_id',
      ));

      $this->addColumn('giftwrap_name', array(
          'header'    => Mage::helper('giftwrap')->__('Gift Wrap Name'),
          'align'     =>'left',
          'index'     => 'giftwrap_name',
      ));

     $store = $this->_getStore();
     $this->addColumn('giftwrap_price', array(
          'header'    => Mage::helper('giftwrap')->__('Price'),
          'align'     =>'left',
          'type'  => 'price',
          'index'     => 'giftwrap_price',
          'currency_code' => $store->getBaseCurrency()->getCode(),
      ));
	 
	 $this->addColumn('giftwrap_image', array(
          'header'    => Mage::helper('giftwrap')->__('Gift Wrap Image'),
          'align'     =>'left',
          'type' 	  => 'image',
          'index'     => 'giftwrap_image',
          'renderer' => 'giftwrap/adminhtml_giftwrap_renderer_image', //get the image HTML code
          'width'	=> '120px',
		  'filter'    => false,
          'sortable'  => false,
      ));
      
      
	$this->addColumn('action',
            array(
                'header'    =>  Mage::helper('giftwrap')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('giftwrap')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
        
	  
      
      $this->addColumn('giftwrap_status', array(
          'header'    => Mage::helper('giftwrap')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'giftwrap_status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        
		
		//$this->addExportType('*/*/exportCsv', Mage::helper('csv')->__('CSV'));
		//$this->addExportType('*/*/exportXml', Mage::helper('csv')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('giftwrap');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('giftwrap')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('giftwrap')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('giftwrap/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('giftwrap')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'giftwrap_status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('giftwrap')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}

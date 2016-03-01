<?php

class Bc_Brand_Block_Adminhtml_Brand_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('brandGrid');
      $this->setDefaultSort('brand_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('brand/brand')->getCollection();
      $this->setCollection($collection);
      $collection->getSelect()->join( array('eaov'=>Mage::getSingleton('core/resource')->getTableName('eav/attribute_option_value')), 'main_table.brand_name = eaov.option_id and store_id=0', array('brand_name'=>'eaov.value'));
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('brand_id', array(
          'header'    => Mage::helper('brand')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'brand_id',
      ));
      
     //get Manufecturer's Attribut options 
     /*$attribute_code=Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', "manufacturer");
     $attributeInfo = Mage::getModel('eav/entity_attribute')->load($attribute_code);
     $attribute_table = Mage::getModel('eav/entity_attribute_source_table')->setAttribute($attributeInfo);
     $attributeOptions = $attribute_table->getAllOptions(false);
     foreach($attributeOptions as $key=>$value){
        $manufacturer[$value['value']]=$value['label'];
     }*/
      //End
      $this->addColumn('brand_name', array(
          'header'    => Mage::helper('brand')->__('Brand Name'),
          'align'     =>'left',
          'index'     =>'brand_name',
          'type'      =>'text',
      ));
      
      $this->addColumn('legend', array(
          'header'    => Mage::helper('brand')->__('Legend'),
          'align'     =>'left',
          'index'     =>'legend',
          'type'      =>'text',
      ));
      
      $this->addColumn('filename', array(
          'header'    => Mage::helper('brand')->__('Thumbnail'),
          'renderer'  => 'brand/adminhtml_grid_renderer1_image',
          'filter'=>false,
          'align'     =>'left',
          'type'  => 'image',
          'width'     => '100px',
          'index'     => 'filename',
      ));

	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('manufacturer')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

      $this->addColumn('status', array(
          'header'    => Mage::helper('brand')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('brand')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('brand')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		//$this->addExportType('*/*/exportCsv', Mage::helper('manufacturer')->__('CSV'));
		//$this->addExportType('*/*/exportXml', Mage::helper('manufacturer')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('brand_id');
        $this->getMassactionBlock()->setFormFieldName('brand');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('brand')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('brand')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('brand/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('brand')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('brand')->__('Status'),
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
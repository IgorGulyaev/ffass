<?php

	class Pixxy_Csvimport_Block_Adminhtml_Logfile_Grid extends Mage_Adminhtml_Block_Widget_Grid{
		
		public function __construct(){
			parent::__construct();
			$this->setId('logfile_grid');
			
			$this->setDefaultSort('id');
			$this->setDefaultDir('desc');
			$this->setSaveParametersInSession(true);
		}
		
		public function getMainButtonsHtml(){
			$label = 'Delete file';
			$action = 'deletefile';
			$fileId = $this->getRequest()->getParam('id');
			if(Mage::getModel('pixxy_csvimport/file')->isActive($fileId)){
				$label = 'Stop import/update and delete file';
				$action = 'stopimport';
			}
			
			$html = parent::getMainButtonsHtml();
    		$addButton = $this->getLayout()->createBlock('adminhtml/widget_button') 
        	->setData(array(
	            'label'     => $label,
	            'onclick'   => "if(confirm('".$label."?')){ setLocation('".$this->getUrl('*/*/'.$action, array('id'=>$fileId))."')}",
	            'class'   => 'delete'
        	))->toHtml();
   	 		return $addButton.$html;
		}
		
		protected function _prepareCollection(){
			$collection = Mage::getModel('pixxy_csvimport/log')->getCollection()->addFieldToFilter('file_id', $this->getRequest()->getParam('id'));
			$this->setCollection($collection);
			return parent::_prepareCollection();
		}
		
		protected function _prepareColumns(){
			$this->addColumn('id', array(
				'header' => Mage::helper('adminhtml')->__('ID'),
				'align' => 'right',
				'width' => '100px',
				'index' => 'id',
			));
			
			
			$this->addColumn('sku', array(
				'header' => Mage::helper('adminhtml')->__('SKU'),
				'align' => 'right',
				'index' => 'sku',
			));
			
			$this->addColumn('error', array(
				'header' => Mage::helper('adminhtml')->__('Error'),
				'index' => 'error',
			));

			/*$this->addColumn('full_error', array(
				'header' => Mage::helper('adminhtml')->__('Message for developer'),
				'index' => 'full_error',
				'column_css_class'=>'no-display',
    			'header_css_class'=>'no-display',
			));*/

			$this->addExportType('*/*/exportCsv', Mage::helper('csvimport')->__('CSV'));
			
			return parent::_prepareColumns();
		}
		
	}
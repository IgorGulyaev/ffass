<?php

	class Pixxy_Csvimport_Block_Adminhtml_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid{
		
		public function __construct(){
			parent::__construct();
			$this->setId('log_grid');
			
			$this->setDefaultSort('id');
			$this->setDefaultDir('desc');
			$this->setSaveParametersInSession(true);
		}
		
		protected function _prepareCollection(){
			$collection = Mage::getModel('pixxy_csvimport/file')->getCollection();
			$collection->getSelect()->joinLeft(
			    array('mappingprofile' => Mage::getConfig()->getTablePrefix().'pixxy_csvimport_mappingprofile'), 'main_table.profile_id = mappingprofile.id',
			    array('profile_name' => 'profile_name')
			)
			->joinLeft( array('log' => Mage::getConfig()->getTablePrefix().'pixxy_csvimport_log'),
	            'main_table.id = log.file_id',
	            array('number_of_ew' => new Zend_Db_Expr('count(log.file_id)'))
            );
            $collection->getSelect()
        ->group('main_table.id');
			;
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
			
			$this->addColumn('profile_name', array(
				'header' => Mage::helper('adminhtml')->__('Mapping Profile'),
				'index' => 'profile_name',
			));
			
			$this->addColumn('filename_original', array(
				'header' => Mage::helper('adminhtml')->__('Filename'),
				'index' => 'filename_original',
			));
			
			$this->addColumn('file_location', array(
				'header' => Mage::helper('adminhtml')->__('File Location'),
				'index' => 'file_location',
			));
			
			$this->addColumn('count_rows', array(
				'header' => Mage::helper('adminhtml')->__('Number of rows'),
				'align' => 'right',
				'index' => 'count_rows',
				'width' => '30px',
				'renderer' => 'Pixxy_Csvimport_Model_Grid_Nor',
			));
			
			$this->addColumn('imported_rows', array(
				'header' => Mage::helper('adminhtml')->__('Proccessed rows'),
				'align' => 'right',
				'index' => 'imported_rows',
				'width' => '30px',
			));
			
			$this->addColumn('number_of_ew', array(
				'header' => Mage::helper('adminhtml')->__('Errors\Warnings'),
				'align' => 'right',
				'index' => 'number_of_ew',
				'filter' => false,
				'width' => '30px',
				'renderer' => 'Pixxy_Csvimport_Model_Grid_Ew',
			));
			
			$this->addColumn('active', array(
				'header' => Mage::helper('adminhtml')->__('Active'),
				'align' => 'right',
				'index' => 'active',
			 	'type' => 'options',
				'options' => Mage::getModel('pixxy_csvimport/grid_renderer')->getYesNoOptions(),
				'renderer' => 'Pixxy_Csvimport_Model_Grid_Yesno',
			));
			
			$this->addColumn('date_uploaded', array(
				'header' => Mage::helper('adminhtml')->__('Date Uploaded'),
				'type' => 'datetime',
				'format' => Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
				'index' => 'date_uploaded',
			));
			
			$this->addColumn('date_import_start', array(
				'header' => Mage::helper('adminhtml')->__('Date import start'),
				'type' => 'datetime',
				'format' => Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
				'index' => 'date_import_start',
			));
			
			$this->addColumn('date_import_end', array(
				'header' => Mage::helper('adminhtml')->__('Date import end'),
				'type' => 'datetime',
				'format' => Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
				'index' => 'date_import_end',
			));
			
			$this->addColumn('user', array(
				'header' => Mage::helper('adminhtml')->__('User'),
				'index' => 'user',
			));

			return parent::_prepareColumns();
		}
		
		public function getRowUrl($row){
	   		return $this->getUrl('*/*/details', array('id' => $row->getId()));
		}
		
	}
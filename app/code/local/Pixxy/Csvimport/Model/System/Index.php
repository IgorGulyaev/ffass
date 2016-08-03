<?php

	class Pixxy_Csvimport_Model_System_Index {
		
		public function toOptionArray(){
	    	$indexes = Mage::getResourceModel('index/process_collection');
	    	$options = array();
	    	foreach ($indexes as $index){
	    		$options[] = array('value'=>$index->getIndexerCode(), 'label'=>$index->getIndexer()->getName());
	    	}
	    	return $options;
	    }
		
	}
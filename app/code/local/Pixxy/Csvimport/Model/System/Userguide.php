<?php

	class Pixxy_Csvimport_Model_System_Userguide extends Mage_Core_Model_Config_Data{
    
		public function getCommentText(Mage_Core_Model_Config_Element $element, $currentValue){
	        $skinUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN);
	 		$result = "
	 			<h4 style='color: green;'>Required attributes for creating new product</h4>
 				<p>- qty <b>(A)</b></p>
 				<p>- attribute_set_id <b>(A)</b> <small>(id or name)</small></p>
 				<p>- type_id <b>(A)</b></p>
 				<p>- name</p> 
				<p>- weight</p>  
				<p>- status</p>  
				<p>- tax_class_id</p> 
				<p>- visibility</p> 
				<p>- price</p> 
				<p>- description</p>  
				<p>- short_description</p> 
				<small style='color: red;'><b>(A)</b> means that you don't need to map this attribute, if this field exist in document it will be automatically mapped</small>
				<br/><br/><br/>
				<h4 style='color: green;'>Automatically mapped attributes while updating (creating new) product</h4>
				<p>- category_ids <b>(A)</b> <small>(id or name)</small></p>
				<p>- parent_sku <b>(A)</b></p>
 				<p>- bridge_images <b>(A)</b> <small>(comma separated image locations)</small></p>
 				<p>- cross_sells_skus <b>(A)</b></p> 
 				<p>- up_sells_skus <b>(A)</b></p>
 				<p>- related_products <b>(A)</b></p>
 				
	 		";
	        return $result;
    	}
		
	}
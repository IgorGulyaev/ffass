<?php

	class Pixxy_Csvimport_Block_Adminhtml_Mapping_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{
		
		protected function _prepareForm(){

			$form = new Varien_Data_Form(array(
	            'id' => 'edit_form',
	            'action' => $this->getUrl('*/*/saveMapping'),
	            'method' => 'post',
				'enctype'=> 'multipart/form-data',
	        ));
	        
	        $form->setUseContainer(true);
		
			$this->setForm($form);
	        
			$fieldset = $form->addFieldset('select_attribute', array(
	            'legend' => Mage::helper('csvimport')->__('')
	        ));
	        
	        $fieldset->addField('profile_name', 'select', array(
	            'label' => Mage::helper('csvimport')->__('Select profile'),
	            'class' => 'required-entry',
	            'required' => true,
	        	'name' => 'profile_name',
	        	'values' => Mage::getModel('pixxy_csvimport/mappingprofile')->getCollection()->addFieldToFilter('hide', array('eq'=>'0'))->toOptionArray(),
	        	'after_element_html' => "
	        		<script>
	        			function getMappedAttributes(){
		        			new Ajax.Request('".$this->getUrl('*/*/getMappedAttributes')."', {
								method:'post',
								parameters: {profile_id: $('profile_name').value},
							  	onSuccess: function(transport) {
								    var response = transport.responseText.evalJSON(true);
								    for (var i = 0; i < response['attributes'].length; i++) {
								    	setChecked(response['attributes'][i]);
								    	setAltMap(response['attributes'][i], response['alt_map'][i]);
								    }
								    setMode(response['mode']);
								    setUpdateStock(response['update_stock']);
								    setFilterTypeIdSelected(response['filter_type_id']);
								    setFilterAttributeSetIdSelected(response['filter_attribute_set_id']);
								    setIndexSelected(response['reindex']);
								},
							  	onFailure: function() { alert('Something went wrong...'); }
							});
	        			}
	        			
	        			Event.observe($('profile_name'), 'change', function(){
	        				clearAll();
	        				getMappedAttributes();
	        			});
	        			
	        			function clearAll(){
	        				var checkbox_list = $$('input[type=checkbox]');
	        				for (var i = 0; i < checkbox_list.length; i++) {
	        					if(checkbox_list[i].disabled == false){
									checkbox_list[i].checked = false;
								}
							}
							$('mode').selectedIndex = 0;
							$('stock').selectedIndex = 1;
							var text_list = $$('input[type=text]');
							for (var i = 0; i < text_list.length; i++) {
								text_list[i].value = '';
							}
							$$('select#filter_type_id option').each(function(o) {
								o.selected = false;
							});
							$$('select#filter_attribute_set_id option').each(function(o) {
								o.selected = false;
							});
							$$('select#reindex option').each(function(o) {
								o.selected = false;
							});
	        			}
	        			
	        			function setChecked(id){
	        				$(id).checked = true;
	        			}
	        			
	        			function setAltMap(id, val){
							$('alt_map_'+id).value = val;
						}
	        			
	        			function setIndexSelected(index){
	        				var r = $(reindex);
	        				for(var i = 0; i<index.length; i++){
		        				for (var j = 0; j<r.length; j++) {
		        					if(r[j].value == index[i]){
		        						r[j].selected = true;
		        					}
		        				}
	        				}
	        			}
	        			
	        			function setFilterTypeIdSelected(index){
	        				if(index != null){
		        				var r = $(filter_type_id);
		        				for(var i = 0; i<index.length; i++){
			        				for (var j = 0; j<r.length; j++) {
			        					if(r[j].value == index[i]){
			        						r[j].selected = true;
			        					}
			        				}
		        				}
	        				}
	        			}
	        			function setFilterAttributeSetIdSelected(index){
	        				if(index != null){
		        				var r = $(filter_attribute_set_id);
		        				for(var i = 0; i<index.length; i++){
			        				for (var j = 0; j<r.length; j++) {
			        					if(r[j].value == index[i]){
			        						r[j].selected = true;
			        					}
			        				}
		        				}
	        				}
	        			}
	        			
	        			function setMode(value){
	        				$(mode)[value-1].selected = true;
	        			}
	        			
	        			function setUpdateStock(value){
	        				var r = $(stock);
	        				for(var i = 0; i<r.length; i++){
	        					if(r[i].value == value){
	        						r[i].selected = true;
	        					}
	        				}
	        			}
	        			
	        			document.observe('dom:loaded', function() {
	        				getMappedAttributes();
	        			});
	        			
	        		</script>
	        	"
	        ));
	        
	        $fieldset_mode = $form->addFieldset('modes', array(
	            'legend' => Mage::helper('csvimport')->__('Mode')
	        ));
	        
	        $fieldset_mode->addField('mode', 'select', array(
	            'label' => Mage::helper('csvimport')->__('Select Mode'),
	            'class' => 'required-entry',
	            'required' => true,
	        	'values' => Mage::getModel('pixxy_csvimport/system_mode')->toOptionArray(),
	        	'name' => 'mode',
	       	));
	       	
	       	//v.1.0.2 - filters
	       	$fieldset_filters = $form->addFieldset('filters', array(
	       		'legend' => Mage::helper('csvimport')->__('Select Filters').
	       		'<p 
       				style="display: inline; position: absolute; right: 50px; background-color: #66FF33; color: black; padding-left: 5px; padding-right:5px; border-radius: 5px; cursor: pointer; -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;" 
       				onclick="if($(filters).getStyle(\'display\')!=\'none\'){$(filters).hide();} else {$(filters).show();}";
	      		>
	       			Show/Hide filters
	       		</p>',
	       	));
	       	
	       	$res = array();
	       	foreach (Mage_Catalog_Model_Product_Type::getOptionArray() as $index => $value) {
	            $res[] = array(
	               'value' => $index,
	               'label' => $value
	            );
       	 	}
	       	$fieldset_filters->addField('filter_type_id', 'multiselect', array(
	       		'label' => Mage::helper('csvimport')->__('Product type'),
	            'required' => false,
	        	'name' => 'filter_type_id[]',
	       		'values' => $res,
	       	));
	       	
	       	$entityTypeId = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
	       	$fieldset_filters->addField('filter_attribute_set_id', 'multiselect', array(
	       		'label' => Mage::helper('csvimport')->__('Attribute Set'),
	            'required' => false,
	        	'name' => 'filter_attribute_set_id[]',
	       		'values' => Mage::getResourceModel('eav/entity_attribute_set_collection')->setEntityTypeFilter($entityTypeId)->toOptionArray(),
	       	));
	       	//end v.1.0.2 - filters
	       	
	       	$fieldset_stock = $form->addFieldset('stock_status', array(
	            'legend' => Mage::helper('csvimport')->__('Stock Status')
	        ));
	        
	        $fieldset_stock->addField('stock', 'select', array(
	            'label' => Mage::helper('csvimport')->__('Update Stock Status'),
	            'class' => 'required-entry',
	            'required' => true,
	        	'values' => array('1'=>'Yes', '0'=>'No'),
	        	'name' => 'stock',
	       	));
	       	
	        $fieldset_attributes = $form->addFieldset('attributes', array(
	            'legend' => Mage::helper('csvimport')->__('Map Attributes')
	        ));
	        
	        $attributes = Mage::helper('csvimport')->getAllMagentoAttributes();
	        foreach ($attributes as $attribute){
	        	$disabled = false;
	        	$checked = false;
	        	if($attribute->getName() == 'sku'){
	        		$checked = true;
	        		$disabled = true;
	        	}
	        	$comment = '';
	        	//if($attribute->getName() == 'image' || $attribute->getName() == 'small_image' || $attribute->getName() == 'thumbnail'){
	        	if(in_array($attribute->getName(), Pixxy_Csvimport_Model_Image::getImageFields())){
	        		$comment = ' This is image field (If selected, image import will start)';
	        		if($attribute->getName() == 'gallery'){
	        			$comment .= '. If there are multiple images, they need to be comma-separated.';
	        		} 
	        	}
	        	$fieldset_attributes->addField($attribute->getId(), 'checkbox', array(
		            'label' => $attribute->getName(),
		            'required' => false,
	        		'checked' => $checked,
	        		'disabled' => $disabled,
		        	'name' => $attribute->getId(),
	        		'after_element_html' => '<input style="margin-left: 20px;" type="text" id="alt_map_'.$attribute->getId().'" name="alt_map_'.$attribute->getId().'"/>'.$comment,
	        	));
	        	
	        }
	        
	        $fieldset_reindex = $form->addFieldset('reindexer', array(
	            'legend' => Mage::helper('csvimport')->__('Reindex data after import/update complete')
	        ));
	        
	        $fieldset_reindex->addField('reindex', 'multiselect', array(
	            'label' => Mage::helper('csvimport')->__('Select Indexer'),
	            'required' => false,
	        	'values' => Mage::getModel('pixxy_csvimport/system_index')->toOptionArray(),
	        	'name' => 'reindex',
	       	));
	        
	        return parent::_prepareForm();
		}
		
	}
<?php

	class Pixxy_Csvimport_Adminhtml_MappingController extends Mage_Adminhtml_Controller_Action{
		
		public function indexAction(){
			$this->loadLayout();
			if(!count(Mage::getModel('pixxy_csvimport/mappingprofile')->getCollection()->addFieldToFilter('hide', array('eq'=>'0')))){
				Mage::getSingleton('adminhtml/session')->addWarning('Please create first mapping profile');
				$this->_redirect('*/*/new');
			}
			$this->renderLayout();
		}

		public function saveMappingAction(){
			if($data = $this->getRequest()->getPost()){
				if(Mage::getModel('pixxy_csvimport/file')->importInProgress()){
					$active_file = Mage::getModel('pixxy_csvimport/file')->getCollection()->addFieldToFilter('active', array('eq'=>'1'))->getFirstItem();
					if((int)$data['profile_name'] == $active_file->getProfileId()){
						Mage::getSingleton('adminhtml/session')->addError('Error while saving attribute list. Import is in progress and this profile is used!');
						$this->_forward('index');
						return;
					}
				}
				try {
					$mapping_attributes = Mage::getModel('pixxy_csvimport/mappingattribute')->getCollection()->addFieldToFilter('mappingprofile_id', (int)$data['profile_name']);
					//delete old mapped attributes
					if(count($mapping_attributes)){
						foreach ($mapping_attributes as $attribute) {
							$attr = Mage::getModel('pixxy_csvimport/mappingattribute')->load($attribute->getId());
							$attr->delete();
						}
					}
					$noattributes = array(
						'form_key',
						'profile_name',
						'mode',
						'stock',
						'reindex',
						'filter_type_id',
						'filter_attribute_set_id',
					);
					//save new mapped attributes
					foreach ($data as $key=>$value){
						if(!in_array($key, $noattributes) && $key != 0 && substr($key, 0, 8) != 'alt_map_'){
							$attribute = Mage::getModel('eav/entity_attribute')->load($key);
							$mappingattribute = Mage::getModel('pixxy_csvimport/mappingattribute');
							$mappingattribute->setMappingprofileId($data['profile_name']);
							$mappingattribute->setMgAttributeCode($attribute->getName());
							$mappingattribute->setMgAttributeId($key);
							$mappingattribute->save();
						}
						else{
							if(substr($key, 0, 8) == 'alt_map_' && !empty($value)){
								//alt mapping
								$mai = substr($key, strrpos($key, '_') + 1);
								$mappingattribute = Mage::getModel('pixxy_csvimport/mappingattribute')->getCollection()->addFieldToFilter('mappingprofile_id', array('eq'=>$data['profile_name']))->addFieldToFilter('mg_attribute_id',array('eq'=>$mai))->getFirstItem();
								if($mappingattribute && $mappingattribute->getId()){
									$mappingattribute->setAltMap($value);
									$mappingattribute->save();
								}
							}
						}
					}
					//save mode and stock
					$profile = Mage::getModel('pixxy_csvimport/mappingprofile')->load($data['profile_name']);
					$profile->setMode($data['mode']);
					$profile->setUpdateStock($data['stock']);
					//filters
					if(isset($data['filter_type_id'])){
						$type_ids = '';
						foreach ($data['filter_type_id'] as $ftid){
							if($ftid != ''){
								$type_ids .= $ftid.',';
							}
						}
						if($type_ids != ''){
							$profile->setFilterTypeId(substr($type_ids, 0, -1));
						}
						else{
							$profile->setFilterTypeId(null);
						}
					}
					else{
						$profile->setFilterTypeId(null);
					}
					if(isset($data['filter_attribute_set_id'])){
						$attribute_set_ids = '';
						foreach ($data['filter_attribute_set_id'] as $fasid){
							if($fasid != ''){
								$attribute_set_ids .= $fasid.',';
							}
						}
						if($attribute_set_ids != ''){
							$profile->setFilterAttributeSetId(substr($attribute_set_ids, 0, -1));
						}
					}
					else{
						$profile->setFilterAttributeSetId(null);
					}
					//end filters
					$profile->save();
					
					//delete, then save reindex
					$reindex_collection = Mage::getModel('pixxy_csvimport/reindex')->getCollection()->addFieldToFilter('mappingprofile_id', array('eq'=>(int)$data['profile_name']));
					if(count($reindex_collection)){
						foreach ($reindex_collection as $reindex){
							$index = Mage::getModel('pixxy_csvimport/reindex')->load($reindex->getId());
							$index->delete();
						}
					}
					if(isset($data['reindex'])){
						foreach ($data['reindex'] as $index) {
							$reindex = Mage::getModel('pixxy_csvimport/reindex');
							$reindex->setMappingprofileId((int)$data['profile_name']);
							$reindex->setIndexCode($index);
							$reindex->save();
						}
					}
					
					Mage::getSingleton('adminhtml/session')->addSuccess('Mapping Profile successfuly saved!');
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError('Error while saving attribute list! '.$e);
				}
			}
			$this->_forward('index');
		}
		
		public function newAction(){
			$this->loadLayout();
			$this->renderLayout();
		}
		
		public function saveMappingProfileAction(){
			if($data = $this->getRequest()->getPost()){
				$notify = false;
				$all_mapping_profiles = Mage::getModel('pixxy_csvimport/mappingprofile')->getCollection();
				foreach ($all_mapping_profiles as $mp){
					if($mp->getProfileName() == $data['profile_name']){
						$notify = true;
					}
				}
				if(!$notify){
					$mapping_profile = Mage::getModel('pixxy_csvimport/mappingprofile');
					$mapping_profile->setProfileName($data['profile_name']);
					$mapping_profile->save();
				}
				else{
					Mage::getSingleton('adminhtml/session')->addError('Profile with same name allready exist! Profile not saved.');
				}
				$this->_forward('index');
			}
		}
		
		public function getMappedAttributesAction(){
			$attributes = array();
			$alt_map = array();
			$json = array();
			if($data = $this->getRequest()->getPost()){
				$mappingattributes = Mage::getModel('pixxy_csvimport/mappingattribute')->getCollection()->addFieldToFilter('mappingprofile_id', (int)$data['profile_id']);
				if(count($mappingattributes)){
					foreach ($mappingattributes as $attr){
						$mgAttribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attr->getMgAttributeCode());
						if($mgAttribute && $mgAttribute->getId()){
							if($mgAttribute->getId() == $attr->getMgAttributeId()){
								$attributes[] = $attr->getMgAttributeId();
								$alt_map[] = $attr->getAltMap();
							}
							else{
								$attr->delete();
							}
						}
						else{
							$attr->delete();
						}
					}
				}
				$mappingprofile = Mage::getModel('pixxy_csvimport/mappingprofile')->load((int)$data['profile_id']);
				$json['attributes'] = $attributes;
				$json['alt_map'] = $alt_map;
				$json['mode'] = $mappingprofile->getMode();
				$json['update_stock'] = $mappingprofile->getUpdateStock();
				
				//filters
				if($mappingprofile->getFilterTypeId()){
					$json['filter_type_id'] = explode(',', $mappingprofile->getFilterTypeId());
				}
				else{
					$json['filter_type_id'] = null;
				}
				if($mappingprofile->getFilterAttributeSetId()){
					$json['filter_attribute_set_id'] = explode(',', $mappingprofile->getFilterAttributeSetId());
				}
				else{
					$json['filter_attribute_set_id'] = null;
				}
				
				$indexArr = array();
				$reindex = Mage::getModel('pixxy_csvimport/reindex')->getCollection()->addFieldToFilter('mappingprofile_id', (int)$data['profile_id']);
				if(count($reindex)){
					foreach ($reindex as $index) {
						$indexArr[] = $index->getIndexCode();
					}
					$json['reindex'] = $indexArr;
				}
			}
			echo json_encode($json);
		}
		
		public function deleteAction(){
			if($profile_name = $this->getRequest()->getParam('profile_name')){
				$mappingprofile = Mage::getModel('pixxy_csvimport/mappingprofile')->getCollection()->addFieldToFilter('profile_name', array('eq'=>$profile_name))->getFirstItem();
				if($mappingprofile && $mappingprofile->getId()){
					try {
						$mappingprofile->setHide('1');
						$mappingprofile->save();
						Mage::getSingleton('adminhtml/session')->addSuccess('Mapping Profile deleted!');
					} catch (Exception $e) {
						Mage::getSingleton('adminhtml/session')->addError('Error while deleting Mapping Profile!');
					}					
				}
				else{
					Mage::getSingleton('adminhtml/session')->addWarning('Mapping Profile don\'t exist!');
				}
			}
			$this->_forward('index');
		}
		
		public function duplicateAction(){
			$profile_name = $this->getRequest()->getParam('profile_name');
			$copy_name = $this->getRequest()->getParam('copy_name');
			if(!empty($profile_name) && !empty($copy_name)){
				$notify = false;
				$all_mapping_profiles = Mage::getModel('pixxy_csvimport/mappingprofile')->getCollection();
				foreach ($all_mapping_profiles as $mp){
					if($mp->getProfileName() == $copy_name){
						$notify = true;
					}
				}
				if(!$notify){
					$mapping_profile = Mage::getModel('pixxy_csvimport/mappingprofile')->getCollection()->addFieldToFilter('profile_name', array('eq'=>$profile_name))->getFirstItem();
					if($mapping_profile && $mapping_profile->getId()){
						try {
							//copy mapping profile
							$new_mapping_profile = Mage::getModel('pixxy_csvimport/mappingprofile');
							$new_mapping_profile->setProfileName($copy_name);
							$new_mapping_profile->setMode($mapping_profile->getMode());
							$new_mapping_profile->setUpdateStock($mapping_profile->getUpdateStock());
							$new_mapping_profile->setHide('0');
							$new_mapping_profile->setFilterTypeId($mapping_profile->getFilterTypeId());
							$new_mapping_profile->setFilterAttributeSetId($mapping_profile->getFilterAttributeSetId());
							$new_mapping_profile->save();
							$new_mapping_profile_id = $new_mapping_profile->getId();
							Mage::getSingleton('adminhtml/session')->addSuccess('Copy of Mapping Profile '.$mapping_profile->getProfileName().' created.');
							
							//copy mapped attributes
							$mapping_attributes = Mage::getModel('pixxy_csvimport/mappingattribute')->getCollection()->addFieldToFilter('mappingprofile_id', (int)$mapping_profile->getId());
							if(count($mapping_attributes)){
								foreach ($mapping_attributes as $mapping_attribute){
									$new_mapping_attribute = Mage::getModel('pixxy_csvimport/mappingattribute');
									$new_mapping_attribute->setMappingprofileId($new_mapping_profile_id);
									$new_mapping_attribute->setMgAttributeCode($mapping_attribute->getMgAttributeCode());
									$new_mapping_attribute->setMgAttributeId($mapping_attribute->getMgAttributeId());
									$new_mapping_attribute->setAltMap($mapping_attribute->getAltMap());
									$new_mapping_attribute->save();
								}
							}
							
							//copy indexers
							$reindex_collection = Mage::getModel('pixxy_csvimport/reindex')->getCollection()->addFieldToFilter('mappingprofile_id', array('eq' => $mapping_profile->getId()));
							if(count($reindex_collection)){
								foreach ($reindex_collection as $reindex){
									$new_reindex = Mage::getModel('pixxy_csvimport/reindex');
									$new_reindex->setMappingprofileId($new_mapping_profile_id);
									$new_reindex->setIndexCode($reindex->getIndexCode());
									$new_reindex->save();
								}
							}
						} catch (Exception $e) {
							Mage::getSingleton('adminhtml/session')->addError('Error while creating a copy of Mapping Profile!');
						}
					}
					else{
						Mage::getSingleton('adminhtml/session')->addError('Mapping Profile don\'t exist!');
					}
				}
				else{
					Mage::getSingleton('adminhtml/session')->addError('Profile with same name allready exist! Profile not saved.');
				}
				
			}
			$this->_forward('index');
		}
		
	}
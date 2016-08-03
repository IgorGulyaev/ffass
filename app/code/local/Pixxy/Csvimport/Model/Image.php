<?php

	class Pixxy_Csvimport_Model_Image extends Mage_Core_Model_Abstract{
		
		const DELETE_ALL_IMAGES = false;
		
		public static function getImageFields(){
			$attributes = Mage::getResourceModel('catalog/product_attribute_collection')->getItems();
		    $image_fields = array();
		    $image_fields[] = 'gallery';
			foreach ($attributes as $attribute){
				if($attribute->getFrontendInput() == 'media_image'){
					$image_fields[] = $attribute->getAttributeCode();
				}
			}
			return $image_fields;
		}
		
		public static function getImageLabels(){
			return array(
				'image_label',
				'small_image_label',
				'thumbnail',
			);
		}
		
		public static function getMappedImageLabels(){
			return array(
				'image' => 'image_label',
				'small_image' => 'small_image_label',
				'thumbnail' => 'thumbnail_label',
			);
		}
		
		public static function getSelectingImageFields(){
			$attributes = Mage::getResourceModel('catalog/product_attribute_collection')->getItems();
		    $image_fields = array();
			foreach ($attributes as $attribute){
				if($attribute->getFrontendInput() == 'media_image'){
					$image_fields[] = $attribute->getAttributeCode();
				}
			}
			return $image_fields;
		}
		
		public static function channelAdvisorImageFields(){
			return array(
				'ITEMIMAGEURL1' => array('image','small_image','thumbnail'),
				'ITEMIMAGEURL2' => '',
				'ITEMIMAGEULR3' => '',
				'ITEMIMAGEURL4' => '',
 			);
		}
		
	}